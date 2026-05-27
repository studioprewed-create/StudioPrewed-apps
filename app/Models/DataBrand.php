<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBrand extends Model
{
    use HasFactory;

    protected $table = 'data_brand';

    protected $fillable = [
        'user_id',
        'nama_brand',
        'logo',
        'category_id',
        'description',
        'email',
        'phone',
        'website',
        'instagram',
        'tiktok',
        'is_active',
    ];

    // =========================
    // RELATION USER
    // =========================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =========================
    // RELATION CATEGORY
    // =========================
    public function category()
    {
        return $this->belongsTo(BrandCategory::class, 'category_id');
    }

    // =========================
    // RELATION ATTIRES
    // =========================
    public function attires()
    {
        return $this->hasMany(Attire::class, 'data_brand_id');
    }
}