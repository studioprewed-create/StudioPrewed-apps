<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkemaKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_client_id',
        'editor_karyawan_id',
        'photografer_karyawan_id',
        'videografer_karyawan_id',
        'makeup_karyawan_id',
        'attire_karyawan_id',
    ];

    public function booking()
    {
        return $this->belongsTo(BookingClient::class, 'booking_client_id');
    }

    public function editor()
    {
        return $this->belongsTo(DataDiriKaryawan::class, 'editor_karyawan_id');
    }

    public function fotografer()
    {
        return $this->belongsTo(DataDiriKaryawan::class, 'photografer_karyawan_id');
    }

    public function videografer()
    {
        return $this->belongsTo(DataDiriKaryawan::class, 'videografer_karyawan_id');
    }

    public function makeup()
    {
        return $this->belongsTo(DataDiriKaryawan::class, 'makeup_karyawan_id');
    }

    public function attire()
    {
        return $this->belongsTo(DataDiriKaryawan::class, 'attire_karyawan_id');
    }
}
