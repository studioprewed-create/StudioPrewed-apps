<?php

namespace App\Helpers;

use Carbon\Carbon;

class SlotHelper
{
    /**
     * Generate slot list berbasis durasi & blokir overlap dengan kapasitas.
     *
     * @param  int   $durasiMenit  contoh: 60 atau 120 (menit)
     * @param  array $booked       [['start'=>'HH:MM','end'=>'HH:MM'], ...]
     * @param  int   $kapasitas    booking maksimum per slot (mis. 2 studio => 2)
     * @return array               [['code','time','available'], ...]
     */
    public static function generateSlotCodes(
        int $durasiMenit = 60,
        array $booked = [],
        int $kapasitas = 1
    ): array {
        // Mulai jam kerja, misal 10:00
        $startOfDay = Carbon::createFromTime(10, 0, 0);

        // Sampai jam berapa? Untuk 60m: 10–15, untuk 120m: 10–16 (sesuai logika lama)
        if ($durasiMenit === 60) {
            $prefix    = '00';
            $totalSlot = 11; // 10–15
        } elseif ($durasiMenit === 120) {
            $prefix    = '01';
            $totalSlot = 5; // 10–16
        } else {
            // fallback dinamis: 10:00–17:00
            $prefix      = '09';
            $totalMenit  = 11 * 60; // 7 jam
            $totalSlot   = max(1, intdiv($totalMenit, $durasiMenit));
        }

        $slots = [];

        for ($i = 1; $i <= $totalSlot; $i++) {
            $begin = $startOfDay->copy()->addMinutes(($i - 1) * $durasiMenit);
            $end   = $begin->copy()->addMinutes($durasiMenit);

            $slot = [
                'code'      => $prefix . str_pad($i, 2, '0', STR_PAD_LEFT),
                'time'      => $begin->format('H:i') . '-' . $end->format('H:i'),
                'available' => true,
            ];

            // Hitung berapa booking yang overlap dengan slot ini
            $overlapCount = 0;

            $checkPoints = [
                $begin->copy()->addMinute(),
                $end->copy()->subMinute()
            ];

            foreach ($checkPoints as $point) {
                $active = 0;

                foreach ($booked as $b) {
                    if (empty($b['start']) || empty($b['end'])) {
                        continue;
                    }

                    $bStart = Carbon::createFromFormat('H:i', substr($b['start'], 0, 5));
                    $bEnd   = Carbon::createFromFormat('H:i', substr($b['end'],   0, 5));

                    // booking aktif di titik waktu ini?
                    if ($point->gte($bStart) && $point->lt($bEnd)) {
                        $active++;
                    }
                }

                $overlapCount = max($overlapCount, $active);
            }

            if ($overlapCount >= $kapasitas) {
                $slot['available'] = false;
            }

            $slots[] = $slot;
        }

        return $slots;
    }
}
