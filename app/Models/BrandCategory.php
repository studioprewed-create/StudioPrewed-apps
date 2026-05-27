<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // =========================
    // RELATION BRANDS
    // =========================
    public function brands()
    {
        return $this->hasMany(DataBrand::class, 'category_id');
    }
}