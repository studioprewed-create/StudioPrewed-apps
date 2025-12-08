<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDiri extends Model
{
    use HasFactory;

    protected $table = 'data_diri';

    protected $fillable = [
        'user_id',

        // data diri utama
        'nama',
        'phone',
        'jenis_kelamin',
        'tanggal_lahir',
        'tanggal_pernikahan',

        // data pasangan
        'nama_pasangan',
        'phone_pasangan',
        'jenis_kelamin_pasangan',
        'tanggal_lahir_pasangan',
    ];

    protected $casts = [
        'tanggal_lahir'           => 'date',
        'tanggal_pernikahan'      => 'date',
        'tanggal_lahir_pasangan'  => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
