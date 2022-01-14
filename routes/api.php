<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/auth/register', 'App\Http\Controllers\API\AuthController@register');

Route::post('/auth/login', 'App\Http\Controllers\API\AuthController@login');

// Route::post('/login', 'App\Http\Controllers\API\UsersController@login');

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/logout', 'App\Http\Controllers\API\AuthController@logout');

    // Account routes
    Route::apiResource('/users', 'App\Http\Controllers\API\UsersController');
    
    // Account routes
    Route::apiResource('/likes', 'App\Http\Controllers\API\LikesController');
    
    // Timeline routes
    Route::apiResource('/posts', 'App\Http\Controllers\API\PostsController');
    Route::apiResource('/comments', 'App\Http\Controllers\API\CommentsController');
});


