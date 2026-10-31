<?php

use App\Http\Controllers\GiftController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuoteController::class, 'index'])->name('home');
Route::get('/explore/{section}', [QuoteController::class, 'index'])
    ->whereIn('section', array_keys(config('sections')))
    ->name('explore');
Route::get('/sayer/{name}', [QuoteController::class, 'sayer'])->name('sayer');
Route::get('/sayer/{name}/stage', [QuoteController::class, 'stage'])->name('sayer.stage');

Route::get('/signup', function () {
    return view('Authentication.SignUp', ['page' => 'Sign Up']);
})->middleware('guest')->name('signup');
Route::post('/signup', [SignupController::class, 'signup']);

Route::get('/login', function () {
    return view('Authentication.LogIn', ['page' => 'Log In']);
})->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
Route::get('/quotes/{quote}', [QuoteController::class, 'show'])->name('quotes.show');
Route::put('/quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
Route::delete('/quotes/{quote}', [QuoteController::class, 'destroy'])->name('quotes.destroy');
Route::post('/quotes/{quote}/light', [QuoteController::class, 'light'])->name('quotes.light');

Route::get('/gift/{gift}', [GiftController::class, 'show'])->name('gifts.show');

Route::middleware('auth')->group(function () {
    Route::get('/gifts', [GiftController::class, 'index'])->name('gifts.index');
    Route::post('/quotes/{quote}/gift', [GiftController::class, 'store'])->name('gifts.store');

    Route::get('/shelf', [ShelfController::class, 'index'])->name('shelf');
    Route::post('/shelf', [ShelfController::class, 'store'])->name('shelf.store');
    Route::delete('/shelf/{book}', [ShelfController::class, 'destroy'])->name('shelf.destroy');
    Route::post('/quotes/{quote}/save', [ShelfController::class, 'save'])->name('shelf.save');
    Route::delete('/shelf/{book}/{quote}', [ShelfController::class, 'remove'])->name('shelf.remove');
});
