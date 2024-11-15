<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\Api\v1\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::get('/login', function () {
    return view('auth.login'); // Ensure you have this view, or redirect accordingly.
})->name('login');
