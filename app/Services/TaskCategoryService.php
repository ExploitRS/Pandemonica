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

        // $task->categories()->attach($cat_id);
        $task->categories()->attach($cat);

        return response()->json([
            'message' => 'Category added successfully',
        ], 200);
    }

    public function update_category(Task $task, Category $cat): JsonResponse
    {
        if ($task->categories()->count() == 0) {
            return response()->json([
                "message" => "This task does not have a category"
            ], 400);
        }

        $task->categories()->sync($cat);

        return response()->json([
            'message' => 'Category updated successfully',
        ], 200);
    }
}
