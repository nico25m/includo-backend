<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::post('/chat', [ChatController::class, 'chat']);
Route::post('/embed-course', [CourseController::class, 'embed']);
