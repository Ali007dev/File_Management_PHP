<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::feature('/',FileController::class);
Route::post('/upload',[FileController::class,'upload']);
