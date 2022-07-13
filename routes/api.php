<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login','App\Http\Controllers\Api\UserController@login');
Route::post('/register',[AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    // Route::get('/users',[UserController::class, 'index']);
    // Route::delete('/users/{id}',[UserController::class, 'destroy']);
    // Route::put('/users/{id}',[UserController::class, 'update']);
    // Route::post('/users',[UserController::class, 'store']);

    // Route::post('/logout',[AuthController::class, 'logout']);
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
});

Route::apiResource('users',UserController::class);
Route::get('/users/{id}',[UserController::class, 'show']);
Route::post('/create',[UserController::class,'create']);
Route::post('/forgot', 'App\Http\Controllers\ForgetPassword@forgot');
Route::post('/reset', 'App\Http\Controllers\ForgetPassword@reset');



// Route::apiResource('users',UserController::class);
