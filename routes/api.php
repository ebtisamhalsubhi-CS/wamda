<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuoteController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::apiResource('quotes', QuoteController::class)->only(['index', 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::apiResource('quotes', QuoteController::class)->only(['store', 'update', 'destroy']);
        Route::post('/quotes/{quote}/light', [QuoteController::class, 'light'])->name('quotes.light');
    });
});