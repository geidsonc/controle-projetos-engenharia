<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectStatusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'App\Http\Controllers\HomeController@show');

Route::apiResource('project', ProjectController::class)
    ->middleware('auth');

Route::apiResource('project.status', ProjectStatusController::class)->only([
    'store'
])
    ->middleware('auth');

Route::apiResource('user', UserController::class)->only([
    'index'
])->middleware('auth');

Route::get('auth/login', 'App\Http\Controllers\AuthController@login');
Route::get('auth/logout', 'App\Http\Controllers\AuthController@logout');


// Route::get('/{any?}', App\Http\Controllers\PagesController::class);
