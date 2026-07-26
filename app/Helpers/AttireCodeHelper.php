<?php

namespace App\Helpers;

use App\Models\AttireCode;
use Illuminate\Validation\ValidationException;

class AttireCodeHelper
{
    public static function generate(int $attireCodeId): array
    {
        $master = AttireCode::query()
            ->whereKey($attireCodeId)
            ->where('active', true)
            ->lockForUpdate()
            ->first();

        if (!$master) {
            throw ValidationException::withMessages([
                'attire_code_id' => 'Kode Attire tidak ditemukan atau sudah tidak aktif.',
            ]);
        }

        $nextNumber = ((int) $master->last_number) + 1;
        $fullCode = $master->formatNumber($nextNumber);

        $master->update([
            'last_number' => $nextNumber,
        ]);

        return [
            'attire_code_id' => $master->id,
            'code_number'    => $nextNumber,
            'kode'           => $fullCode,
        ];
    }
}