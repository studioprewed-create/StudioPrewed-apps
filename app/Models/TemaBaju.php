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
    public function getImagesArrayAttribute()
    {
        if (!$this->images) {
            return [];
        }

        $decoded = json_decode($this->images, true);
        return is_array($decoded) ? $decoded : [];
    }
    public function getMainImageAttribute()
    {
        $images = $this->images_array;
        if (count($images) === 0) {
            return 'https://via.placeholder.com/400x220?text=No+Image';
        }

        $first = ltrim($images[0], '/');
        $diskPath = public_path('storage/' . $first);
        if (file_exists($diskPath)) {
            return asset('public/storage/' . $first);
        }

        return 'https://via.placeholder.com/400x220?text=No+Image';
    }
    public function getAllImageUrlsAttribute()
    {
        $images = $this->images_array;
        if (count($images) === 0) {
            return ['https://via.placeholder.com/400x220?text=No+Image'];
        }

        return collect($images)->map(function ($path) {
            $clean = ltrim($path, '/');
            $diskPath = public_path('storage/' . $clean);

            if (file_exists($diskPath)) {
                return asset('public/storage/' . $clean);
            }

            return 'https://via.placeholder.com/400x220?text=No+Image';
        })->all();
    }
}
