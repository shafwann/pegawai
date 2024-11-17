<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Http\Resources\FormatApi;

class ApiPegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();
        return new FormatApi(true, 'Data Pegawai berhasil diambil', $pegawai);
    }
}
