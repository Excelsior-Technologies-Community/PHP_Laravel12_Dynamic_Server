<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServerController;

Route::get('/',                        [ServerController::class, 'index'])->name('servers.index');
Route::get('/servers/create',          [ServerController::class, 'create'])->name('servers.create');
Route::post('/servers',                [ServerController::class, 'store'])->name('servers.store');
Route::get('/servers/{id}/edit',       [ServerController::class, 'edit'])->name('servers.edit');
Route::put('/servers/{id}',            [ServerController::class, 'update'])->name('servers.update');
Route::delete('/servers/{id}',         [ServerController::class, 'destroy'])->name('servers.destroy');
Route::get('/servers/{id}/connect',    [ServerController::class, 'connect'])->name('servers.connect');
Route::get('/servers/{id}/table-data', [ServerController::class, 'tableData'])->name('servers.table-data');
Route::get('/servers/{id}/status',     [ServerController::class, 'status'])->name('servers.status');
Route::get('/servers/{id}/export',     [ServerController::class, 'export'])->name('servers.export');
Route::get('/servers/{id}/query',      [ServerController::class, 'queryRunner'])->name('servers.query');
Route::post('/servers/{id}/query',     [ServerController::class, 'runQuery'])->name('servers.run-query');
Route::get('/logs',                    [ServerController::class, 'logs'])->name('servers.logs');
