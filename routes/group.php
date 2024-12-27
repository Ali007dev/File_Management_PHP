<?php

use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::feature('/',GroupController::class);

Route::get('/show-by/{group}',[GroupController::class,'showById']);


