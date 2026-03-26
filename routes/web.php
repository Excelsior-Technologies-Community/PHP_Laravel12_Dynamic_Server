<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ServerController;


Route::get('/servers', [ServerController::class, 'index'])->name('servers.index');
Route::get('/servers/create', [ServerController::class, 'create'])->name('servers.create');
Route::post('/servers', [ServerController::class, 'store'])->name('servers.store');
Route::get('/servers/connect/{id}', [ServerController::class, 'connect'])->name('servers.connect');

Route::get('/', function () {
    return view('welcome');
});
