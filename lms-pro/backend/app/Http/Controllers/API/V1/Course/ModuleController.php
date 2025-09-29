<?php

namespace App\Http\Controllers\API\V1\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\Course\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        return $course->modules;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $module = $course->modules()->create($request->all());

        return response()->json($module, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, Module $module)
    {
        return $module;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Module $module)
    {
        $request->validate([
            'title' => 'string|max:255',
        ]);

        $module->update($request->all());

        return response()->json($module, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Module $module)
    {
        $module->delete();

        return response()->json(null, 204);
    }
}