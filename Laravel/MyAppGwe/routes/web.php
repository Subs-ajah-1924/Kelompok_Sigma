<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hi;
use App\Http\Controllers\MahasiswaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ok', function(){
    return view("hallo");
});

Route::get('/hai', [Hi::class, 'sapa']);

Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'show']);
Route::post('/mahasiswa', [MahasiswaController::class, 'store']);