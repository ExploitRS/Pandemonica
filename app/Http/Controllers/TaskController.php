<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;

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

    }

    public function show(Task $task) {
        return response()->json($task);
    }

    public function update(StoreTaskRequest $request, Task $task) {
        $task->update($request->all());
        $task->save();

        // return response()->json($task);
    }

    public function destroy(Task $task) {
        $deleted = $task->delete();

        return response()->json($deleted, 204);
    }
}
