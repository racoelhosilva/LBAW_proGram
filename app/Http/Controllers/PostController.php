<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Group;
use App\Models\Post;
use App\Models\Tag;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    const ALLOWED_TAGS = '<p><br><strong><em><u><code><ol><ul><li><span><div><a><img><iframe>';

    protected function cleanupUnusedImages(string $text, Post $post)
    {
        preg_match_all('/<(img|iframe)[^>]+>/i', $text, $result);

        $folder = 'temporary/'.$post->author_id;

        // files from the temporary folder + the postAttachments folder
        $allFiles = Storage::disk('storage')->files($folder) + Storage::disk('storage')->files('postAttachments/'.$post->id.'/'.$post->author_id);

        if (empty($result[0])) {
            foreach ($allFiles as $file) {
                Storage::disk('storage')->delete($file);
            }
            Storage::disk('storage')->deleteDirectory($folder);
            Storage::disk('storage')->deleteDirectory('postAttachments/'.$post->id);

            return [];
        }

        $usedFiles = [];
        foreach ($result[0] as $img) {
            preg_match('/src="([^"]+)"/', $img, $match);
            $usedFiles[] = ltrim(parse_url($match[1], PHP_URL_PATH), '/');
        }

        $unusedFiles = array_diff($allFiles, $usedFiles);
        Debugbar::info($unusedFiles);

        foreach ($unusedFiles as $file) {
            Storage::disk('storage')->delete($file);
        }

        return $usedFiles;

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): RedirectResponse|View|Factory
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', Post::class);

        return view('pages.create-post', [
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ?int $groupId = null): RedirectResponse|Redirector
    {
        $group = $groupId ? Group::findOrFail($groupId) : null;

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', Post::class);

        $request->validate([
            'title' => 'required|string',
            'text' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable',
            'is_announcement' => 'nullable',
        ]);

        $post = new Post;
        DB::transaction(function () use ($post, $group, $request) {
            // Save every field except for the text
            $post->author_id = Auth::id();
            $post->title = $request->input('title');
            $post->is_public = $group ? $group->is_public : $request->input('is_public', false);
            $post->is_announcement = $request->input('is_announcement', false);
            $post->save();
            $post->tags()->sync($request->input('tags'));

            $postId = $post->id;

            // Find the used images, and delete the unused ones
            $text = strip_tags($request->input('text'), self::ALLOWED_TAGS);
            $usedFiles = $this->cleanupUnusedImages($text, $post);
            $text = str_replace('temporary', 'postAttachments/'.$postId, $text);

            // Move the used images to the postAttachments folder
            foreach ($usedFiles as $file) {
                $newFile = str_replace('temporary', 'postAttachments/'.$postId, $file);
                Storage::disk('storage')->move($file, $newFile);
            }

            // Delete the temporary folder
            Storage::disk('storage')->deleteDirectory('temporary/'.$post->author_id);

            // Save the text
            $post->text = $text;
            $post->save();

            $group?->posts()->attach($post->id);
        });

        return redirect()->route('post.show', $post->id)->withSuccess('Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id): View
    {
        $post = Post::with('author')->findOrFail($id);

        $this->authorize('view', $post);
        $this->authorize('viewAny', Comment::class);

        $comments = $this->getComments($post);

        if ($request->ajax()) {
            return view('partials.comment-list', ['comments' => $comments, 'showEmpty' => false]);
        }

        return view('pages.post', ['post' => $post, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): RedirectResponse|View|Factory
    {

        $post = Post::findOrFail($id);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $post);
        session(['previous_url' => url()->previous()]);

        return view('pages.edit-post', [
            'post' => $post,
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): Redirector|RedirectResponse
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string',
            'text' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable|boolean',
            'is_announcement' => 'nullable|boolean',
        ]);
        $post = Post::findOrFail($id);

        $text = strip_tags($request->input('text'), self::ALLOWED_TAGS);
        $usedFiles = $this->cleanupUnusedImages($text, $post);
        $text = str_replace('temporary', 'postAttachments/'.$post->id, $text);
        foreach ($usedFiles as $file) {
            $newFile = str_replace('temporary', 'postAttachments/'.$post->id, $file);
            Storage::disk('storage')->move($file, $newFile);
        }
        Storage::disk('storage')->deleteDirectory('temporary/'.$post->author_id);

        DB::transaction(function () use ($post, $request, $text) {
            $post->title = $request->input('title', $post->title);
            $post->text = $text;
            $post->is_public = $request->filled('is_public');
            $post->is_announcement = $request->filled('is_announcement');

            $post->save();

            $post->tags()->sync($request->input('tags'));
        });

        return redirect()->route('post.show', $post->id)->withSuccess('Post updated successfully.');
    }

    private function getComments(Post $post)
    {
        return Comment::with('author')
            ->where('post_id', $post->id)
            ->orderBy('timestamp', 'DESC')
            ->simplePaginate(15);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $post = Post::findOrFail($id);

        $this->authorize('forceDelete', $post);

        $post->delete();

        return redirect()->route('user.show', $post->author_id)->withSuccess('Post deleted successfully.');
    }
}
