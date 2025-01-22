<?php

use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::feature('/',GroupController::class);


Route::get('index', [GroupController::class, 'index']);
Route::get('show/{id}', [GroupController::class, 'show']);
Route::get('all', [GroupController::class, 'allForCurrentUser']);
Route::post('create', [GroupController::class, 'create']);
Route::put('update/{id}', [GroupController::class, 'update'])->middleware('isAdminInGroup');
Route::delete('delete/{ids}', [GroupController::class, 'delete'])->middleware('isAdminInGroup');
;



Route::get('/show-by/{group}',[GroupController::class,'showById'])
->middleware('isUserInGroup');


