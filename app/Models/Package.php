<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga',
        'durasi',
        'images',
        'discount',
        'notes',
        'konsep',
        'rules',
        'order',
        'active',
    ];

    protected $casts = [
        'harga'    => 'decimal:2',
        'discount' => 'decimal:2',
        'durasi'   => 'integer',
        'order'    => 'integer',
        'active'   => 'boolean',
    ];

    // ⬇⬇⬇ ini yang kita sesuaikan untuk HOSTINGER
    public function getImageUrlAttribute()
    {
        if (!$this->images) {
            return 'https://via.placeholder.com/400x220?text=No+Image';
        }

        $storagePath = public_path('storage/' . $this->images);

        if (file_exists($storagePath)) {
            return asset('public/storage/' . $this->images);
        }

        return 'https://via.placeholder.com/400x220?text=No+Image';
    }

    public function getFinalPriceAttribute()
    {
        if ($this->discount > 0) {
            return $this->harga - ($this->harga * $this->discount / 100);
        }
        return $this->harga;
    }
}
