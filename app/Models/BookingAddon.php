<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAddon extends Model
{
    protected $fillable = [
        'booking_client_id',
        'addon_id',
        'qty',
        'harga_satuan',
        'total_harga',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(BookingClient::class, 'booking_client_id');
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}
