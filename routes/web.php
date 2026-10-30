<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AjusteController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SucursalController;
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

// RUTA DE LAS SUCURSALES
Route::get('/admin/sucursales', [SucursalController::class, 'index'])->name('admin.sucursales.index')->middleware('auth');
Route::post('/admin/sucursales/create', [SucursalController::class, 'store'])->name('admin.sucursales.store')->middleware('auth');
Route::put('/admin/sucursales/{id}', [SucursalController::class, 'update'])->name('admin.sucursales.update')->middleware('auth');
Route::delete('/admin/sucursales/{id}', [SucursalController::class, 'destroy'])->name('admin.sucursales.destroy')->middleware('auth');

// RUTAS DE LOS LABORATORIOS
Route::get('/admin/laboratorios', [App\Http\Controllers\LaboratorioController::class, 'index'])->name('admin.laboratorios.index')->middleware('auth');
Route::post('/admin/laboratorios', [App\Http\Controllers\LaboratorioController::class, 'store'])->name('admin.laboratorios.store')->middleware('auth');
Route::put('/admin/laboratorios/{id}', [App\Http\Controllers\LaboratorioController::class, 'update'])->name('admin.laboratorios.update')->middleware('auth');
Route::delete('/admin/laboratorios/{id}', [App\Http\Controllers\LaboratorioController::class, 'destroy'])->name('admin.laboratorios.destroy')->middleware('auth');

// RUTAS PARA LAS FORMAS FARMACEUTICAS
Route::get('/admin/forma-farmaceuticas', [App\Http\Controllers\FormaFarmaceuticaController::class, 'index'])->name('admin.forma_farmaceuticas.index')->middleware('auth');
Route::post('/admin/forma-farmaceuticas/create', [App\Http\Controllers\FormaFarmaceuticaController::class, 'store'])->name('admin.forma_farmaceuticas.store')->middleware('auth');
Route::put('/admin/forma-farmaceuticas/{id}', [App\Http\Controllers\FormaFarmaceuticaController::class, 'update'])->name('admin.forma_farmaceuticas.update')->middleware('auth');
Route::delete('/admin/forma-farmaceuticas/{id}', [App\Http\Controllers\FormaFarmaceuticaController::class, 'destroy'])->name('admin.forma_farmaceuticas.destroy')->middleware('auth');

// RUTA DE LOS EMPLEADOS
Route::get('/admin/empleados', [EmpleadosController::class, 'index'])->name('admin.empleados.index')->middleware('auth');
Route::post('/admin/empleados/create', [EmpleadosController::class, 'store'])->name('admin.empleados.store')->middleware('auth');
Route::put('/admin/empleados/{id}', [EmpleadosController::class, 'update'])->name('admin.empleados.update')->middleware('auth');
Route::delete('/admin/empleados/{id}', [EmpleadosController::class, 'destroy'])->name('admin.empleados.destroy')->middleware('auth');

// RUTA DE LAS CATEGORIAS
Route::get('/admin/categorias', [CategoriasController::class, 'index'])->name('admin.categorias.index')->middleware('auth');
Route::post('/admincategoriass/create', [CategoriasController::class, 'store'])->name('admin.categorias.store')->middleware('auth');
Route::put('/admin/categorias/{id}', [CategoriasController::class, 'update'])->name('admin.categorias.update')->middleware('auth');
Route::delete('/admin/categorias/{id}', [CategoriasController::class, 'destroy'])->name('admin.categorias.destroy')->middleware('auth');




