<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('login-google',[\App\Http\Controllers\SocialiteLoginController::class,'redirectToProvider'])->name('login-google');
Route::get('login/google/callback',[\App\Http\Controllers\SocialiteLoginController::class,'handleProviderCallback']);
