<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marquee extends Model
{
    protected $table = 'marquees';

    protected $fillable = [
        'text',        // isi pill
        'icon_class',  // optional (e.g. "fa-solid fa-heart")
        'order',       // urutan tampil
        'active',      // boolean
    ];

    protected $casts = [
        'active' => 'boolean',
        'order'  => 'integer',
    ];
}
