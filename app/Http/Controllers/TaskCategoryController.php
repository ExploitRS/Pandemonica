<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryIdsRequest;
use App\Models\Task;
use App\Models\Category;
use App\Services\TaskCategoryService;

class TaskCategoryController extends Controller
{
    private $service;

    public function __construct(TaskCategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'The task is not found',
            ], 404);
        }

        $categories = $task->categories;
        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryIdsRequest $request, int $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'message' => 'The task is not found',
            ], 404);
        }

        $cat_id = $request->input('category_ids')[0]['category_id'];
        $cat = Category::find($cat_id);

        return $this->service->add_category($task, $cat);
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $task_id, int $category_int)
    {
        $task = Task::find($task_id);
        if (!$task) {
            return response()->json([
                'message' => 'The task is not found',
            ], 404);
        } else if (!$task->categories()->find($category_int)) {
            return response()->json([
                'message' => 'The category is not associated with the task',
            ], 404);
        }

        $task->categories()->detach($category_int);

        return response()->json([
            'message' => 'The category of the task were deleted successfully',
        ], 200);
    }
}
