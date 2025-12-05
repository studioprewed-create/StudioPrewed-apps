<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $fillable = [
        'nama',
        'kode',
        'kategori',
        'deskripsi',
        'harga',
        'durasi',     // baru
        'kapasitas',  // baru
        'is_active',
    ];

    protected $casts = [
        'harga'     => 'integer',
        'durasi'    => 'integer',
        'kapasitas' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ((int) $this->kategori) {
            1       => 'Extra Slot Waktu',
            2       => 'Tema Baju Tambahan',
            3       => 'Fitur Tambahan',
            default => 'Unknown',
        };
    }

    // optional, cuma buat tampilan aja
    public function getDurasiLabelAttribute(): ?string
    {
        if (!$this->durasi) return null;

        $jam   = intdiv($this->durasi, 60);
        $menit = $this->durasi % 60;

        if ($jam && $menit) return "{$jam} jam {$menit} menit";
        if ($jam)          return "{$jam} jam";
        return "{$menit} menit";
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
