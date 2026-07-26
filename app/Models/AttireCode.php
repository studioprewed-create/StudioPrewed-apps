<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttireCode extends Model
{
    use HasFactory;

    protected $table = 'attire_codes';

    protected $fillable = [
        'name',
        'prefix',
        'separator',
        'digit_length',
        'last_number',
        'order',
        'active',
    ];

    protected $casts = [
        'digit_length' => 'integer',
        'last_number'  => 'integer',
        'order'        => 'integer',
        'active'       => 'boolean',
    ];

    protected $appends = [
        'next_code_preview',
    ];

    public function attires()
    {
        return $this->hasMany(
            TemaBaju::class,
            'attire_code_id'
        );
    }

    public function formatNumber(int $number): string
    {
        return strtoupper($this->prefix)
            . ($this->separator ?? '')
            . str_pad(
                (string) $number,
                (int) $this->digit_length,
                '0',
                STR_PAD_LEFT
            );
    }

    public function getNextCodePreviewAttribute(): string
    {
        return $this->formatNumber(
            ((int) $this->last_number) + 1
        );
    }
}