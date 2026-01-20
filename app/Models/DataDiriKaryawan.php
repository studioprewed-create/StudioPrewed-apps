<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDiriKaryawan extends Model
{
    protected $table = 'data_diri_karyawan';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'role',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_pernikahan',
        'kewarganegaraan',
        'alamat',
        'no_hp',
        'foto',
        'status_karyawan',
        'tanggal_masuk',
        'tanggal_keluar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
