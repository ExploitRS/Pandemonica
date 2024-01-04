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
    public function index(Task $task)
    {
        $categories = $task->categories;
        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Task $task, CategoryIdsRequest $request)
    {
        $cat_id = $request->input('category_ids')[0]['category_id'];
        $cat = Category::find($cat_id);

        // return $this->add_category($task, $cat);
        // return $this->serv->add_category($task, $cat);
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
    public function destroy(Task $task)
    {
        $task->categories()->detach();

        return response()->json([
            'message' => 'The category of the task were deleted successfully',
        ], 200);
    }
}
