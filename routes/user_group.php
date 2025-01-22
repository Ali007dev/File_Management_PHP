<?php

use App\Http\Controllers\UserGroupController;
use Illuminate\Support\Facades\Route;

Route::get('index', [UserGroupController::class, 'index']);
Route::get('show/{id}', [UserGroupController::class, 'show']);
Route::get('all', [UserGroupController::class, 'all']);
Route::post('create', [UserGroupController::class, 'create'])->middleware('isAdminInGroup');
Route::put('update/{id}', [UserGroupController::class, 'update'])->middleware('isAdminInGroup');
Route::delete('delete/{ids}', [UserGroupController::class, 'delete'])->middleware('isAdminInGroup');
