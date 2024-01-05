<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

use App\Services\TaskCategoryService;

class TaskController extends Controller
{
    private $service;

    public function __construct(TaskCategoryService $service)
    {
        $this->service = $service;
    }
    //
    public function index() {
        $tasks = Task::with('categories')->get();
        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request) {
        $task = new Task([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'is_done' => $request->is_done ?? false,
        ]);

        $task->save();

        $response = ['task' => $task];

        if ($request->has('category_ids')) {
            $cat_id = $request->input('category_ids')[0]['category_id'];
            $cat = Category::find($cat_id);
            $added = $this->service->add_category($task, $cat);
            $response['category'] = $added->original;
        }

        return response()->json($response, 201);
    }

    public function show(Task $task) {
        $response = ['task' => $task];
        $response['category'] = $task->categories()->first();
        return response()->json($response);
    }

    public function update(UpdateTaskRequest $request, Task $task) {
        $task->update($request->except('category_ids'));
        $task->save();

        $response = ['task' => $task];

        if ($request->has('category_ids')) {
            $cat_id = $request->input('category_ids')[0]['category_id'];
            $cat = Category::find($cat_id);
            $updated = $this->service->update_category($task, $cat);
            $response['category'] = $updated->original;
        }

        return response()->json($response, 200);
    }

    public function destroy(Task $task) {
        $deleted = $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ], 200);
    }
}
