<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\JabatanController;

Route::get('/', [PegawaiController::class, 'index']);
Route::post('/store', [PegawaiController::class, 'store'])->name('store');
Route::get('/pegawai/{id}', [PegawaiController::class, 'detail'])->name('pegawai.detail');
Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

Route::put('/update/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');

Route::get('/jabatan', [JabatanController::class, 'index']);
