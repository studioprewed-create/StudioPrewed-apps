<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'aboutus';

    protected $fillable = [
        'model_type',
        'title',
        'subtitle',
        'description',
        'image',  // JSON array
        'order',
        'active',
    ];

    protected $casts = [
        'image' => 'array', // otomatis decode/encode array JSON
        'active' => 'boolean',
        'order'  => 'integer',
    ];

    /**
     * Scope untuk hanya mengambil data aktif
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Accessor untuk mendapatkan URL gambar pertama
     */
    public function getImageUrlAttribute()
    {
        if (is_array($this->image) && count($this->image) > 0) {
            return asset('storage/' . $this->image[0]);
        }

        return asset('asset/IMGhome/default.jpg');
    }

    /**
     * Accessor untuk mendapatkan semua URL gambar
     */
    public function getAllImageUrlsAttribute()
    {
        if (is_array($this->image) && count($this->image) > 0) {
            return collect($this->image)->map(fn($img) => asset('storage/' . $img))->toArray();
        }

        return [asset('asset/IMGhome/default.jpg')];
    }
}
