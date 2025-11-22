<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (){
    Route::view('/', 'home')->name('home');
    Route::view('users', 'users')->name('users');
    Route::view('rooms', 'rooms')->name('rooms');
    Route::view('calendar', 'calendar')->name('calendar');
});
