<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

use Illuminate\Http\Request;

class TaskController extends Controller
{
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

    public function add_category(Task $task, Category $cat) {
        // Restrict each task to one category as per the specified requirements
        if  ($task->categories()->count() > 0) {
            throw new \Exception('This task already has a category');
        }
        if  ($task->categories()->count() == 0) {
            throw new \Exception(¢task->categories());
        }

        task->categories()->attach($cat);
    }
}
