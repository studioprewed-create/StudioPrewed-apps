<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = ['title','description','image','order','active','category'];

    protected $casts = [
        'active' => 'boolean',
        'order'  => 'integer',
    ];

    // optional: gampangin akses URL gambar di Blade
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }
}
