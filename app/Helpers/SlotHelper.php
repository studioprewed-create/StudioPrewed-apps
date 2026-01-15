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

        // Mulai jam kerja
        $startOfDay = Carbon::createFromTime(10, 0, 0); // 10:00
        $endOfDay   = Carbon::createFromTime(21, 0, 0); // 21:00

        // =============================
        // Tentukan total slot
        // =============================
        $totalMenit = $endOfDay->diffInMinutes($startOfDay);

        if ($durasiMenit === 60) {
            $prefix    = '00';
            $totalSlot = intdiv($totalMenit, 60);   // 11 slot → 10:00 - 21:00
        } elseif ($durasiMenit === 120) {
            $prefix    = '01';
            $totalSlot = intdiv($totalMenit, 120);  // 5 slot → 10:00 - 21:00
        } else {
            // durasi custom (addon dll)
            $prefix    = '09';
            $totalSlot = max(1, intdiv($totalMenit, $durasiMenit));
        }

        $slots = [];

        for ($i = 1; $i <= $totalSlot; $i++) {
            $begin = $startOfDay->copy()->addMinutes(($i - 1) * $durasiMenit);
            $end   = $begin->copy()->addMinutes($durasiMenit);

            // ❗ jangan bikin slot lewat jam 21:00
            if ($end->gt($endOfDay)) {
                break;
            }

            $slot = [
                'code'      => $prefix . str_pad($i, 2, '0', STR_PAD_LEFT),
                'time'      => $begin->format('H:i') . '-' . $end->format('H:i'),
                'available' => true,
            ];

            // =============================
            // Hitung overlap kapasitas
            // =============================
            $overlapCount = 0;
            $checkPoints = [$begin, $end];

            foreach ($checkPoints as $point) {
                $active = 0;

                foreach ($booked as $b) {
                    if (empty($b['start']) || empty($b['end'])) continue;

                    $bStart = Carbon::createFromFormat('H:i', substr($b['start'], 0, 5));
                    $bEnd   = Carbon::createFromFormat('H:i', substr($b['end'],   0, 5));

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
