<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DESCPackage extends Model
{
    protected $table = 'desc_packages';

    protected $fillable = [
        'content',
        'active',
    ];
}