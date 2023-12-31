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

    public function store(StoreTaskRequest $req) {
        $task = Task::create([
            'name' => req('name'),
            'description' => req('description'),
            'due_date' => req('due_date'),
        ]);
    }
}
