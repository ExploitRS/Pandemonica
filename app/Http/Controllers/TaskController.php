<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
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
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request) {
        $task = new Task([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        $task->save();

        return response()->json($task, 201);
    }

    public function show(Task $task) {
        return response()->json($task);
    }

    public function update(UpdateTaskRequest $request, Task $task) {
        $task->update($request->all());
        $task->save();

        return response()->json($task, 200);
    }

    public function destroy(Task $task) {
        $deleted = $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ], 200);
    }
}
