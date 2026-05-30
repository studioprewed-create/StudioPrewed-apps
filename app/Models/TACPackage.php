<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TACPackage extends Model
{
    use HasFactory;

    protected $table = 'tac_packages';

    protected $fillable = [
        'package_id',
        'content',
        'order',
        'active',
    ];

    protected $casts = [
        'order'  => 'integer',
        'active' => 'boolean',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}