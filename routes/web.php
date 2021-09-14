<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('employees');
})->name('dashboard');

Route::middleware(['auth:sanctum','verified'])->get('/employees', function () {
    return view('employees');
})->name('employees');
