<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiPegawaiController;
use App\Http\Controllers\ApiJabatanController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('pegawai', [ApiPegawaiController::class, 'index']);

Route::get('jabatan', [ApiJabatanController::class, 'index']);
