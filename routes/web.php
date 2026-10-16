<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AjusteController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [Admin::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin', [Admin::class, 'index'])->name('admin.index')->middleware('auth');

// RUTA DE AJUSTES
Route::get('/admin/ajustes', [AjusteController::class, 'index'])->name('admin.ajustes.index')->middleware('auth');
Route::post('/admin/ajustes/create', [AjusteController::class, 'store'])->name('admin.ajustes.store')->middleware('auth');

//RUTA DE ROLES
Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth');
Route::post('/admin/roles/create', [RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth');
Route::put('/admin/roles/{id}', [RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth');
Route::delete('/admin/roles/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth');

// RUTA DE USUARIOS
Route::get('/admin/users', [App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index')->middleware('auth');
Route::post('/admin/users/create', [App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store')->middleware('auth');
Route::put('/admin/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update')->middleware('auth');
Route::delete('/admin/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy')->middleware('auth');


