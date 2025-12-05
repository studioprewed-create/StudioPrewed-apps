<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemaBaju extends Model
{
    use HasFactory;

    protected $table = 'tema_baju';

    protected $fillable = [
        'nama',
        'images',
        'detail',
        'designer',
        'harga',
        'kode',
        'ukuran',
        'tipe',
        'order',
        'active',
    ];

    protected $casts = [
        'harga'  => 'decimal:2',
        'order'  => 'integer',
        'active' => 'boolean',
    ];

    /**
     * Accessor: $temaBaju->images_array
     * mengembalikan array path gambar dari kolom JSON `images`
     */
    public function getImagesArrayAttribute()
    {
        if (!$this->images) {
            return [];
        }

        $decoded = json_decode($this->images, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Helper: ambil satu gambar utama (untuk thumbnail)
     */
    public function getMainImageAttribute()
    {
        $images = $this->images_array;
        if (count($images) === 0) {
            return 'https://via.placeholder.com/400x220?text=No+Image';
        }

        $first = $images[0];

        // kalau path ada di public/
        if (file_exists(public_path($first))) {
            return asset($first);
        }

        // kalau ada di storage/app/public
        if (file_exists(storage_path('app/public/' . $first))) {
            return asset('storage/' . $first);
        }

        return 'https://via.placeholder.com/400x220?text=No+Image';
    }
}
