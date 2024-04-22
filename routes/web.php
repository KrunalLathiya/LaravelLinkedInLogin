<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkedinController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('auth/linkedin', [LinkedinController::class, 'linkedinRedirect']);
Route::get('auth/linkedin/callback', [LinkedinController::class, 'linkedinCallback']);
