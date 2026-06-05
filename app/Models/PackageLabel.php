<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageLabel extends Model
{
    protected $table = 'package_labels';

    protected $fillable = [
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}