<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleReview extends Model
{
    protected $fillable = [

        'review_id',
        'author_name',
        'rating',
        'review_text',
        'profile_photo',
        'review_images',
        'likes_count',
        'review_date'
    ];
}
