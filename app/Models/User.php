<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
    
    public function dataDiri()
    {
     return $this->hasOne(DataDiri::class);
    }

    public function bookings()
    {
        return $this->hasMany(\App\Models\BookingClient::class, 'user_id');
    }

    public function dataDiriKaryawan()
    {
        return $this->hasOne(DataDiriKaryawan::class);
    }

}
