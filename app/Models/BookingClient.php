<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingClient extends Model
{
    use HasFactory;

    protected $table = 'booking_clients';

    protected $fillable = [
        'user_id',

        // Identitas
        'nama_cpp',
        'nama_cpw',
        'email_cpp',
        'email_cpw',
        'phone_cpp',
        'phone_cpw',
        'alamat_cpp',
        'alamat_cpw',

        // Sosmed
        'ig_cpp',
        'ig_cpw',
        'tiktok_cpp',
        'tiktok_cpw',
        'sosmed_lain',

        // Paket & harga
        'package_id',
        'package_price',
        'addons_total',
        'grand_total',

        // Slot utama
        'photoshoot_date',
        'slot_code',
        'photoshoot_slot',
        'start_time',
        'end_time',

        // Extra slot (addon kategori 1)
        'extra_slot_code',
        'extra_photoshoot_slot',
        'extra_start_time',
        'extra_end_time',
        'extra_minutes',

        // Tema utama
        'tema_id',
        'tema_nama',
        'tema_kode',

        // Tema tambahan (addon kategori 2)
        'tema2_id',
        'tema2_nama',
        'tema2_kode',

        // Style & lainnya
        'style',
        'wedding_date',
        'notes',

        // Ringkasan
        'nama_gabungan',
        'email_gabungan',
        'phone_gabungan',

        // Kode & status
        'kode_pesanan',
        'status',
    ];

    protected $casts = [
        'photoshoot_date' => 'date',
        'wedding_date'    => 'date',
        'sosmed_lain'     => 'array',
        // kalau mau, bisa juga:
        // 'extra_start_time' => 'datetime:H:i:s',
        // 'extra_end_time'   => 'datetime:H:i:s',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function tema()
    {
        // Tema utama
        return $this->belongsTo(TemaBaju::class, 'tema_id');
    }

    public function temaTambahan()
    {
        // Tema tambahan (addon kategori 2)
        return $this->belongsTo(TemaBaju::class, 'tema2_id');
    }

    public function skemaKerja()
    {
        return $this->hasOne(\App\Models\SkemaKerja::class, 'booking_client_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper / Accessor kecil (optional)
    |--------------------------------------------------------------------------
    */

    // Nama gabungan fallback kalau kolom nama_gabungan kosong
    public function getDisplayNamaGabunganAttribute(): string
    {
        if (!empty($this->nama_gabungan)) {
            return $this->nama_gabungan;
        }

        $cpp = trim($this->nama_cpp ?? '');
        $cpw = trim($this->nama_cpw ?? '');

        return trim(($cpp ? $cpp : 'CPP') . ' & ' . ($cpw ? $cpw : 'CPW'));
    }

    // Total addon dalam format rupiah (optional, buat view)
    public function getAddonsTotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format((int) $this->addons_total, 0, ',', '.');
    }

    public function getGrandTotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format((int) $this->grand_total, 0, ',', '.');
    }
}
