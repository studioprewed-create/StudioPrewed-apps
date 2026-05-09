<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [

        'customer_name',
        'photo_date',

        'favorite_services',
        'recommendation_score',
        'feedback',
    ];

    protected $casts = [

        'favorite_services' => 'array',

        'photo_date' => 'date',

    ];
}