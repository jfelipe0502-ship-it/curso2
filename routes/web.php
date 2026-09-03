<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

Route::get('/', [PostController::class, 'index'])->name('portada');
Route::get('/avisos', [PostController::class, 'index'])->name('avisos.index');

Route::middleware('auth')->group(function () {
    Route::get('/avisos/crear', [PostController::class, 'create'])->name('avisos.create');
    Route::post('/avisos', [PostController::class, 'store'])->name('avisos.store');
    Route::get('/avisos/{post}/editar', [PostController::class, 'edit'])->name('avisos.edit');
    Route::put('/avisos/{post}', [PostController::class, 'update'])
        ->name('avisos.update')
        ->can('update', 'post');
    Route::delete('/avisos/{post}', [PostController::class, 'destroy'])->name('avisos.destroy');
});

Route::get('/contacto', fn () => view('contacto'));

Route::get('/login', [AuthController::class, 'mostrar'])->name('login');
Route::post('/login', [AuthController::class, 'entrar']);
Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registrar']);
Route::post('/logout', [AuthController::class, 'salir'])->name('logout');

