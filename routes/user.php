<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::feature('/',UserController::class);
Route::get('/report/{user}',[UserController::class,'report'])
->middleware('isAdminInGroup');

Route::get('/me',[UserController::class,'me']);
Route::get('notification/all',[UserController::class,'notification']);
