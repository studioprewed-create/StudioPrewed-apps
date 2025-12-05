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
        'order',   // 👈 tambahan
        'active',  // 👈 tambahan
    ];

    /**
     * Casts
     */
    protected $casts = [
        'harga'    => 'decimal:2',
        'discount' => 'decimal:2',
        'durasi'   => 'integer',
        'order'    => 'integer', // 👈 tambahan (optional tapi enak)
        'active'   => 'boolean', // 👈 tambahan
    ];

    /**
     * Accessor: $package->image_url
     * Mengembalikan url gambar yang bisa langsung dipakai di tag <img>.
     * - Jika file disimpan di public/uploads/... -> akan mengembalikan asset(path)
     * - Jika tidak ada file -> fallback ke placeholder external
     */
    public function getImageUrlAttribute()
    {
        // jika kolom images kosong => placeholder
        if (!$this->images) {
            return 'https://via.placeholder.com/400x220?text=No+Image';
        }

        // jika path ada di public (contoh: 'uploads/packages/xxx.jpg')
        if (file_exists(public_path($this->images))) {
            return asset($this->images);
        }

        // fallback kalau pakai storage:link (contoh: 'packages/xxx.jpg' di storage/app/public)
        if (file_exists(storage_path('app/public/' . $this->images))) {
            return asset('storage/' . $this->images);
        }

        // kalau semua gagal => placeholder
        return 'https://via.placeholder.com/400x220?text=No+Image';
    }

    // ✅ accessor harga final (harga setelah diskon)
    public function getFinalPriceAttribute()
    {
        if ($this->discount > 0) {
            return $this->harga - ($this->harga * $this->discount / 100);
        }
        return $this->harga;
    }
}
