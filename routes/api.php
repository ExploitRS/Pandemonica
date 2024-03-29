<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('json')->group(function () {
//     Route::apiResource('tasks', TaskController::class);
// });
Route::prefix('todo')->group(function () {
    Route::apiResources([
        'tasks' => TaskController::class,
        'categories' => CategoryController::class,
    ]);
    Route::apiresource('tasks.categories', TaskCategoryController::class)->except(['show']);
});

// Route::resource('tasks.categories');