<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;

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
Route::resource('tasks', TaskController::class);
Route::resource('tasks/categories', CategoryController::class);

// Route::resource('tasks.categories');