<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class TaskCategoryService
{
    public function add_category(Task $task, Category $cat): JsonResponse
    {
        // Restrict each task to one category as per the specified requirements
        if  ($task->categories()->count() > 0) {
            return response()->json([
                "message" => "This task already has a category"
            ], 400);
        }

        $task->categories()->attach($cat);

        return response()->json($cat, 201);
    }

    public function update_category(Task $task, Category $cat): JsonResponse
    {
        $task->categories()->sync($cat);

        return response()->json($cat, 200);
    }
}
