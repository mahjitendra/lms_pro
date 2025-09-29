<?php

namespace App\Http\Controllers\API\V1\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\Lesson;
use App\Models\Course\Module;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Module $module)
    {
        return $module->lessons;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Module $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $lesson = $module->lessons()->create($request->all());

        return response()->json($lesson, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module, Lesson $lesson)
    {
        return $lesson;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module, Lesson $lesson)
    {
        $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
        ]);

        $lesson->update($request->all());

        return response()->json($lesson, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module, Lesson $lesson)
    {
        $lesson->delete();

        return response()->json(null, 204);
    }
}