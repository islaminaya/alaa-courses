<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

Route::livewire('/courses/{course}', 'pages::courses.show')->name('courses.show');
Route::livewire('/courses', 'pages::courses.show')->name('courses.learn');
