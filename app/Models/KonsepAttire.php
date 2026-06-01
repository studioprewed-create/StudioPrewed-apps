<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonsepAttire extends Model
{
    protected $table = 'konsep_attires';

    protected $fillable = [
        'content',
        'active',
    ];
}