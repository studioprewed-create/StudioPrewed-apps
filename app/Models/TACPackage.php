<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TACPackage extends Model
{
    use HasFactory;

    protected $table = 'tac_packages';

    protected $fillable = [
        'content',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}