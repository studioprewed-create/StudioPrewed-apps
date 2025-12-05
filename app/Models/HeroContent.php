<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroContent extends Model
{
    // nama tabel (opsional, karena Laravel otomatis pakai 'hero_contents')
    protected $table = 'hero_contents';

    // field yang boleh diisi mass-assignment
    protected $fillable = [
        'image',
        'active',
        'order',
    ];
}
