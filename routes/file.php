<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::feature('/',FileController::class);
Route::post('/upload',[FileController::class,'uploadOrModify']);
Route::post('/upload/{file}',[FileController::class,'uploadOrModify']);
Route::get('/download/{file}',[FileController::class,'downloadFile']);
Route::get('/report/{file}',[FileController::class,'report']);
