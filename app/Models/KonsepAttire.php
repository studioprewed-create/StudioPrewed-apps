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

    public function attires()
    {
        return $this->hasMany(
            TemaBaju::class,
            'konsep_attire_id'
        );
    }
}