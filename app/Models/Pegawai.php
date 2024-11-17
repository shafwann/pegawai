<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    /** @use HasFactory<\Database\Factories\PegawaiFactory> */
    use HasFactory;

    protected $table = 'pegawai';
    protected $fillable = ['nama', 'foto_profile', 'jabatan', 'tanggal_dipekerjakan', 'tanggal_berhenti', 'gaji'];
    protected $casts = [
        'jabatan' => 'array',
        'tanggal_dipekerjakan' => 'date',
        'tanggal_berhenti' => 'date',
    ];
    protected $hidden = ['created_at', 'updated_at'];
}
