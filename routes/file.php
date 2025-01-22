<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('index', [FileController::class, 'index']);
Route::get('show/{id}', [FileController::class, 'show']);
Route::get('all', [FileController::class, 'all']);
Route::post('create', [FileController::class, 'create']);
Route::put('update/{id}', [FileController::class, 'update']);
Route::delete('delete/{ids}', [FileController::class, 'delete'])->middleware('isOwnerOfFile');
Route::post('/upload/{file}',[FileController::class,'uploadOrModify'])
->middleware('isUserInGroup');


Route::get('/download/{ids}',[FileController::class,'downloadFile'])
->middleware('isUserInGroup');


Route::get('/report/{file}',[FileController::class,'report']);


Route::get('/open/{file}',[FileController::class,'openFile']);

Route::get('/get-archive/{file}',[FileController::class,'getArchive']);

Route::get('/compare/{oldId}', [FileController::class, 'compare'])
     ->middleware('isUserInGroup');
Route::get('/archive/{oldId}',[FileController::class,'archive'])
->middleware('isUserInGroup');



