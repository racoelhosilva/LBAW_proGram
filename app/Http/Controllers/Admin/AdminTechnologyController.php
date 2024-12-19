<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\Request;

class AdminTechnologyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $technologies = Technology::query();

        if (! empty($request->input('query'))) {
            $pattern = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $request->input('query')).'%';

            $technologies = $technologies->where('name', 'ILIKE', $pattern);

            if (is_numeric($request->input('query'))) {
                $technologies = $technologies->orWhere('id', $request->input('query'));
            }
        }

        $technologies = $technologies->orderBy('id')->paginate(20);

        return view('admin.pages.technology', ['technologies' => $technologies]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'technology' => 'required|string|max:255|unique:technology,name',
        ]);
        $technology = new Technology;
        $technology->name = $request->input('technology');
        $technology->save();

        return redirect()->route('admin.technology.index')->withSuccess('Technology created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Technology $technology)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technology $technology)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technology $technology)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        $technology = Technology::findOrFail($id);
        $technology->delete();

        return redirect()->route('admin.technology.index')->withSuccess('Technology deleted successfully.');
    }
}
