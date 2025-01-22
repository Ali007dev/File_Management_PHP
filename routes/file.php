<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::feature('/',FileController::class);
Route::post('/upload',[FileController::class,'uploadOrModify']);
Route::post('/upload/{file}',[FileController::class,'uploadOrModify']);
Route::get('/download/{ids}',[FileController::class,'downloadFile']);
Route::get('/report/{file}',[FileController::class,'report']);
Route::get('/open/{file}',[FileController::class,'openFile']);
Route::get('/get-archive/{file}',[FileController::class,'getArchive']);
Route::get('/compare/{oldId}',[FileController::class,'compare']);
Route::get('/archive/{oldId}',[FileController::class,'archive']);


