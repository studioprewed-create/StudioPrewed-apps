<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttireDetail extends Model
{
    use HasFactory;

    protected $table = 'attire_details';

    protected $fillable = [
        'tema_baju_id',
        'group',
        'content',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function attire()
    {
        return $this->belongsTo(
            TemaBaju::class,
            'tema_baju_id'
        );
    }
}