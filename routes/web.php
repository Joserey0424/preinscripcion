<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportacionController;
use App\Http\Controllers\FechaController;
use App\Http\Controllers\AsignacionController;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('fechas', FechaController::class);

Route::get('/importaciones', [ImportacionController::class, 'index'])
    ->name('importaciones.index');

Route::post('/importaciones/preview', [ImportacionController::class, 'preview'])
    ->name('importaciones.preview');

Route::post('/importaciones/importar', [ImportacionController::class, 'importar'])
    ->name('importaciones.importar');

Route::get('/asignaciones', [AsignacionController::class, 'index'])
    ->name('asignaciones.index');

Route::patch(
    '/fechas/{fecha}/cupo',
    [FechaController::class, 'actualizarCupo']
)->name('fechas.cupo');

Route::patch(
    '/fechas/{fecha}/estado',
    [FechaController::class, 'cambiarEstado']
)->name('fechas.estado');


Route::post(
    '/importaciones/importar',
    [ImportacionController::class,'importar']
)->name('importaciones.importar');

Route::get(
    '/asignaciones/buscar',
    [AsignacionController::class, 'buscar']
)->name('asignaciones.buscar');

Route::get('/asignaciones', [AsignacionController::class, 'index'])
    ->name('asignaciones.index');

Route::get('/asignaciones/buscar', [AsignacionController::class, 'buscar'])
    ->name('asignaciones.buscar');

Route::put('/asignaciones/{asignacion}', [AsignacionController::class, 'update'])
    ->name('asignaciones.update');

Route::delete('/asignaciones/{asignacion}', [AsignacionController::class, 'destroy'])
    ->name('asignaciones.destroy');