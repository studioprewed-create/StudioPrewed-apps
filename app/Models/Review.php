<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['name','role','avatar','rating','content','date','active'];
    protected $casts = ['active' => 'boolean'];
}
