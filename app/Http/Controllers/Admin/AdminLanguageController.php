<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class AdminLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $languages = Language::query();

        if (! empty($request->input('query'))) {
            $pattern = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $request->input('query')).'%';

            $languages = $languages->where('name', 'ILIKE', $pattern);

            if (is_numeric($request->input('query'))) {
                $languages = $languages->orWhere('id', $request->input('query'));
            }
        }

        $languages = $languages->orderBy('id')->paginate(20);

        return view('admin.pages.language', ['languages' => $languages]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'language' => 'required|string|max:255|unique:language,name',
        ]);
        $language = new Language;
        $language->name = $request->input('language');
        $language->save();

        return redirect()->route('admin.language.index')->withSuccess('Language created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        $language = Language::findOrFail($id);
        $language->delete();

        return redirect()->route('admin.language.index')->withSuccess('Language deleted successfully.');
    }
}
