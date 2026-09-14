<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
require __DIR__.'/auth.php';

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

require __DIR__.'/auth.php';

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

// Modulo Ajustes
Route::get('/admin/ajustes', [\App\Http\Controllers\AjusteController::class, 'index'])->name('admin.ajustes.index')->middleware('auth');
Route::post('/admin/ajustes/create', [\App\Http\Controllers\AjusteController::class, 'store'])->name('admin.ajustes.store')->middleware('auth');


// Modulo Roles
Route::get('/admin/roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth');
Route::post('/admin/roles/create', [\App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth');
Route::put('/admin/roles/{id}/edit', [\App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth');
Route::delete('/admin/roles/{id}/delete', [\App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth');

// permisos
Route::get('/admin/roles/{id}/permisos', [\App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('auth');
Route::put('/admin/roles/{id}/permisos/toggle', [\App\Http\Controllers\RoleController::class, 'togglePermisos'])->name('admin.roles.togglePermisos')->middleware('auth');

// usuarios
Route::get('/admin/usuarios', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.usuarios.index')->middleware('auth');
Route::post('/admin/usuarios/create', [\App\Http\Controllers\UserController::class, 'store'])->name('admin.usuarios.store')->middleware('auth');
Route::put('/admin/usuarios/{id}/edit', [\App\Http\Controllers\UserController::class, 'update'])->name('admin.usuarios.update')->middleware('auth');
Route::delete('/admin/usuarios/{id}/delete', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth');



