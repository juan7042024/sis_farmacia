<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [Admin::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin', [Admin::class, 'index'])->name('admin.index')->middleware('auth');
