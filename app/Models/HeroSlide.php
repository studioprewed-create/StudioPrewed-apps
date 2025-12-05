<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = ['title','subtitle','image','order','active'];
    protected $casts = ['active' => 'boolean'];
}
