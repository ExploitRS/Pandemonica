<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryIdsRequest;
use App\Models\Task;
use App\Models\Category;
use App\Http\Controllers\TaskController;

class TaskCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Task $task, CategoryIdsRequest $request)
    {
        $cat_id = $request->input('category_ids')[0]['category_id'];
        echo $cat_id;
        $cat = Category::find($cat_id);
        add_category($task, $cat);
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
    public function destroy(string $id)
    {
        //
    }
}
