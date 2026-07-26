<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemaBaju extends Model
{
    use HasFactory;

    protected $table = 'tema_baju';

    protected $fillable = [
        'nama',

        'attire_code_id',
        'code_number',
        'kode',

        'data_brand_id',
        'konsep_attire_id',
        'label_ids',

        'images',
        'harga',

        'ukuran_pria',
        'ukuran_wanita',
        'warna',

        'status',
        'order',
        'active',

        // Legacy sementara
        'detail',
        'designer',
        'ukuran',
        'tipe',
    ];

    protected $casts = [
        'images'      => 'array',
        'label_ids'   => 'array',
        'harga'       => 'decimal:2',
        'code_number' => 'integer',
        'order'       => 'integer',
        'active'      => 'boolean',
    ];

    protected $appends = [
        'main_image',
    ];

    public function codeMaster()
    {
        return $this->belongsTo(
            AttireCode::class,
            'attire_code_id'
        );
    }

    public function designerBrand()
    {
        return $this->belongsTo(
            DataBrand::class,
            'data_brand_id'
        );
    }

    public function tipeAttire()
    {
        return $this->belongsTo(
            KonsepAttire::class,
            'konsep_attire_id'
        );
    }

    public function details()
    {
        return $this->hasMany(
            AttireDetail::class,
            'tema_baju_id'
        )->orderBy('order');
    }

    public function getLabelItemsAttribute()
    {
        $ids = $this->label_ids ?? [];

        if (empty($ids)) {
            return collect();
        }

        return PackageLabel::query()
            ->whereIn('id', $ids)
            ->where('active', true)
            ->get();
    }

    public function getImagesArrayAttribute(): array
    {
        if (is_array($this->images)) {
            return array_values($this->images);
        }

        if (!$this->images) {
            return [];
        }

        $decoded = json_decode($this->images, true);

        return is_array($decoded)
            ? array_values($decoded)
            : [];
    }

    public function getMainImageAttribute(): string
    {
        $images = $this->images_array;

        if (count($images) === 0) {
            return 'https://via.placeholder.com/400x220?text=No+Image';
        }

        $first = ltrim($images[0], '/');
        $diskPath = public_path('storage/' . $first);

        if (file_exists($diskPath)) {
            return asset('public/storage/' . $first);
        }

        return 'https://via.placeholder.com/400x220?text=No+Image';
    }

    public function getAllImageUrlsAttribute(): array
    {
        $images = $this->images_array;

        if (count($images) === 0) {
            return [
                'https://via.placeholder.com/400x220?text=No+Image',
            ];
        }

        return collect($images)
            ->map(function ($path) {
                $clean = ltrim($path, '/');
                $diskPath = public_path(
                    'storage/' . $clean
                );

                if (file_exists($diskPath)) {
                    return asset(
                        'public/storage/' . $clean
                    );
                }

                return 'https://via.placeholder.com/400x220?text=No+Image';
            })
            ->all();
    }
}