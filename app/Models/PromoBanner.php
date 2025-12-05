<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBanner extends Model
{
    protected $fillable = ['image','order','active'];
    protected $casts = ['active' => 'boolean'];
}