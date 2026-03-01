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

      if ($durasiMenit === 60) {
            $prefix     = '00';
            $stepMenit  = 60;
            $endOfDay   = Carbon::createFromTime(17, 0, 0);
        } elseif ($durasiMenit === 120) {
            $prefix     = '01';
            $stepMenit  = 60; // ⬅️ INI YANG DIUBAH (rolling per 1 jam)
            $endOfDay   = Carbon::createFromTime(17, 0, 0);
        } else {
            $prefix     = '09';
            $stepMenit  = 60;
            $endOfDay   = Carbon::createFromTime(17, 0, 0);
        }

        $slots = [];
        $current = $startOfDay->copy();
        $i = 1;

        while ($current->copy()->addMinutes($durasiMenit)->lte($endOfDay)) {
            $begin = $current->copy();
            $end   = $begin->copy()->addMinutes($durasiMenit);

            $slot = [
                'code'      => $prefix . str_pad($i, 2, '0', STR_PAD_LEFT),
                'time'      => $begin->format('H:i') . '-' . $end->format('H:i'),
                'available' => true,
            ];

            // ===== CEK OVERLAP (biarkan seperti punyamu) =====
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

            $current->addMinutes($stepMenit); // ⬅️ rolling 1 jam
            $i++;
        }

        return $slots;
            }
}
