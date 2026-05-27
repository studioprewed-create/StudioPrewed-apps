<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use App\Helpers\SlotHelper;
use App\Models\User;
use App\Models\Package;
use App\Models\TemaBaju;
use App\Models\HeroSlide;
use App\Models\Marquee;
use App\Models\AboutUs;
use App\Models\Service;
use App\Models\Review;
use App\Models\HeroContent;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\PromoBanner;
use App\Models\BookingClient;
use App\Models\Addon;
use App\Models\BookingAddon;
use App\Models\DataDiri;
use App\Models\DataDiriKaryawan;
use \App\Models\SkemaKerja;
use \App\Models\Survey;
use App\Models\GoogleReview;
use App\Models\BrandCategory;

class EXECUTIVEController extends Controller
{
    public function dashboard(Request $request){ return $this->loadPage($request, 'Dashboard'); }
    public function jadwalkerja(Request $request){ return $this->loadPage($request, 'JadwalKerja'); }
    public function jadwalpesanan(Request $request){ return $this->loadPage($request, 'JadwalPesanan'); }
    public function catalogue(Request $request){ return $this->loadPage($request, 'Catalogue'); }
    public function galleryattire(Request $request){ return $this->loadPage($request, 'GalleryAttire'); }
    public function statistik(Request $request){ return $this->loadPage($request, 'Statistik'); }
    public function statistiksurvey(Request $request){ return $this->loadPage($request, 'StatistikContent.StatistikSurvey'); }
    public function statistikreview(Request $request){ return $this->loadPage($request, 'StatistikContent.StatistikReview'); }
    public function statistikpengeluaran(Request $request){ return $this->loadPage($request, 'StatistikContent.StatistikPengeluaran'); }
    public function statistikpendapatan(Request $request){ return $this->loadPage($request, 'StatistikContent.StatistikPendapatan'); }
    public function statistikkinerja(Request $request){ return $this->loadPage($request, 'StatistikContent.StatistikKinerja'); }
    public function statistikkatalog(Request $request){ return $this->loadPage($request, 'StatistikContent.StatistikKatalog'); }
    public function partnership(Request $request){ return $this->loadPage($request, 'Brand.Partnership'); }
    public function dataPartnership(Request $request){ return $this->loadPage($request, 'Brand.DataPartnership'); }
    public function kategoriPartnership(Request $request){ return $this->loadPage($request, 'Brand.KategoriPartnership'); }
    public function upload(Request $request){ return $this->loadPage($request, 'Upload'); }
    public function dataakun(Request $request){ return $this->loadPage($request, 'DataAkun'); }
    public function menuHomeDashboard(Request $request){ return $this->loadPage($request, 'MenuPanel.HomePages.Dashboard'); }
    public function menuHomePortofolio(Request $request){ return $this->loadPage($request, 'MenuPanel.HomePages.Portofolio'); }
    public function menuHomePricelist(Request $request){ return $this->loadPage($request, 'MenuPanel.HomePages.Pricelist'); }
    public function menuBerkas(Request $request){ return $this->loadPage($request, 'MenuPanel.Berkas'); }

    private function nextOrder(string $modelClass): int
        {
            $max = $modelClass::max('order');
            return is_null($max) ? 0 : ((int)$max + 1);
        }

    private function cleanOrder($raw, string $modelClass, $fallback = null): int
        {
            if (is_numeric($raw)) return (int)$raw;
            if (is_numeric($fallback)) return (int)$fallback;
            return $this->nextOrder($modelClass);
        }
    private function applyFilters($query, array $searchFields, ?string $q, ?string $status, ?string $sort, string $defaultOrderCol = 'order', ?string $alphaField = null)
        {
            if ($q !== null && $q !== '') {
                $query->where(function ($w) use ($q, $searchFields) {
                    foreach ($searchFields as $i => $field) {
                        $i === 0 ? $w->where($field, 'like', '%'.$q.'%') : $w->orWhere($field, 'like', '%'.$q.'%');
                    }
                });
            }

            if ($status === '1') $query->where('active', 1);
            elseif ($status === '0') $query->where('active', 0);

            $alpha = $alphaField ?: ($searchFields[0] ?? $defaultOrderCol);

            switch ($sort) {
                case 'order_desc':   $query->orderBy($defaultOrderCol, 'desc'); break;
                case 'order_asc':    $query->orderBy($defaultOrderCol, 'asc'); break;
                case 'created_desc': $query->orderBy('created_at', 'desc'); break;
                case 'created_asc':  $query->orderBy('created_at', 'asc'); break;
                case 'updated_desc': $query->orderBy('updated_at', 'desc'); break;
                case 'updated_asc':  $query->orderBy('updated_at', 'asc'); break;
                case 'alpha_desc':   $query->orderBy($alpha, 'desc'); break;
                case 'alpha_asc':    $query->orderBy($alpha, 'asc'); break;
                default:             $query->orderBy($defaultOrderCol, 'asc'); break;
            }

            return $query;
        }

    public function apiSlots(Request $request)
        {
            $date      = $request->query('date');             // format: Y-m-d (wajib)
            $pkgId     = $request->query('package_id');       // id paket (untuk slot utama)
            $durasiQ   = (int) $request->query('durasi', 0);  // durasi manual (addon)
            $exclude   = $request->query('exclude');          // kode slot utama (optional, untuk extra)
            $mainStart = $request->query('main_start');       // "HH:MM" (optional, hanya untuk extra)
            $mainEnd   = $request->query('main_end');         // "HH:MM" (optional, hanya untuk extra)

            if (!$date) {
                return response()->json([], 400);
            }

            // =====================================
            // Tentukan durasi slot (menit)
            // =====================================
            if ($durasiQ > 0) {
                // mode EXTRA SLOT (addon kategori 1)
                $durasiMenit = $durasiQ;
            } elseif ($pkgId) {
                // mode SLOT UTAMA: ambil dari paket
                $package     = Package::find($pkgId);
                $durasiMenit = (int) ($package?->durasi ?? $package?->durasi_menit ?? 0);
                if ($durasiMenit <= 0) {
                    $durasiMenit = 120; // fallback aman
                }
            } else {
                $durasiMenit = 60;
            }

            // =====================================
            // Ambil booking yg sudah ada hari itu
            // SEKARANG: ikut hitung extra_start_time / extra_end_time
            // =====================================
            $kapasitasPerSlot = 2; // 2 studio

            $bookedRaw = BookingClient::whereDate('photoshoot_date', $date)
                ->get(['start_time', 'end_time', 'extra_start_time', 'extra_end_time']);

            $booked = [];

            foreach ($bookedRaw as $b) {
                // slot utama
                if ($b->start_time && $b->end_time) {
                    $booked[] = [
                        'start' => substr((string) $b->start_time, 0, 5),
                        'end'   => substr((string) $b->end_time,   0, 5),
                    ];
                }
                // slot extra dari addon kategori 1
                if ($b->extra_start_time && $b->extra_end_time) {
                    $booked[] = [
                        'start' => substr((string) $b->extra_start_time, 0, 5),
                        'end'   => substr((string) $b->extra_end_time,   0, 5),
                    ];
                }
            }

            // =====================================
            // Generate slot dasar (dengan kapasitas)
            // =====================================
            $slots = SlotHelper::generateSlotCodes($durasiMenit, $booked, $kapasitasPerSlot);
            // $slots: masing-masing elemen minimal: ['code' => ..., 'time' => "HH:MM-HH:MM", 'available' => bool]

            // =====================================
            // Aturan tambahan utk extra slot:
            // 1) slot dengan code yg sama dgn $exclude -> selalu tidak available
            // 2) kalau dikirim main_start & main_end (extra slot):
            //    semua slot yang overlap jam slot utama -> tidak available
            // =====================================
            if ($exclude || ($mainStart && $mainEnd)) {
                foreach ($slots as &$slot) {
                    // 1) tidak boleh pakai slot utama lagi
                    if ($exclude && isset($slot['code']) && $slot['code'] === $exclude) {
                        $slot['available'] = false;
                        continue;
                    }

                    // 2) extra slot *tidak boleh* jatuh di dalam / overlap range slot utama
                    if ($mainStart && $mainEnd && !empty($slot['time'])) {
                        [$s, $e] = array_map('trim', explode('-', $slot['time'])); // "HH:MM-HH:MM"

                        // overlap: s < mainEnd && e > mainStart
                        if ($s < $mainEnd && $e > $mainStart) {
                            $slot['available'] = false;
                        }
                    }
                }
                unset($slot);
            }

            return response()->json($slots);
        }
    public function getTemaByName(Request $request)
        {
            $nama        = $request->query('nama');          // optional
            $date        = $request->query('date');          // optional
            $start       = $request->query('start');         // optional "HH:MM"
            $end         = $request->query('end');           // optional "HH:MM"
            $excludeKode = $request->query('exclude_kode');  // optional – misal kode tema utama

            $query = TemaBaju::query();

            if ($nama) {
                $query->where('nama', $nama);
            }

            $temaList = $query
                ->select('id', 'nama', 'kode')
                ->orderBy('nama')
                ->orderBy('kode')
                ->get();

            $usedCodes = [];

            // Cek tema yang sudah dipakai di jam yang sama
            if ($date && $start && $end) {
                $bookings = BookingClient::whereDate('photoshoot_date', $date)
                    ->where(function ($q) {
                        // sekarang cek tema utama & tema tambahan
                        $q->whereNotNull('tema_kode')
                        ->orWhereNotNull('tema2_kode');
                    })
                    ->where(function ($q) use ($start, $end) {
                        $q->whereTime('start_time', '<', $end)
                        ->whereTime('end_time',   '>', $start);
                    })
                    ->get(['tema_kode', 'tema2_kode']);

                foreach ($bookings as $b) {
                    if (!empty($b->tema_kode)) {
                        $usedCodes[] = $b->tema_kode;
                    }
                    if (!empty($b->tema2_kode)) {
                        $usedCodes[] = $b->tema2_kode;
                    }
                }
            }

            if ($excludeKode) {
                // paksa kode ini dianggap "sudah terpakai" (misalnya tema utama booking ini sendiri)
                $usedCodes[] = $excludeKode;
            }

            $usedCodes = array_values(array_unique(array_filter($usedCodes)));

            $result = $temaList->map(function ($t) use ($usedCodes) {
                $kode = $t->kode ?? '';

                return [
                    'id'        => $t->id,
                    'nama'      => $t->nama,
                    'kode'      => $kode,
                    'available' => $kode
                        ? !in_array($kode, $usedCodes, true)
                        : true,
                ];
            });

            return response()->json($result);
        }
    public function bookingClientStore(Request $request)
        {
            $v = $request->validate([
                // Step 1
                'nama_cpp'   => 'required|string|max:100',
                'phone_cpp'  => 'required|string|max:30',
                'email_cpp'  => 'nullable|email|max:120',
                'alamat_cpp' => 'nullable|string|max:255',

                'nama_cpw'   => 'required|string|max:100',
                'phone_cpw'  => 'required|string|max:30',
                'email_cpw'  => 'nullable|email|max:120',
                'alamat_cpw' => 'nullable|string|max:255',

                // Step 2 - utama
                'package_id'      => 'required|exists:packages,id',
                'photoshoot_date' => 'required|date|after_or_equal:today',
                'slot_code'       => 'required|string|max:10',
                'photoshoot_slot' => 'required|string|max:20', // "HH:MM-HH:MM"
                'start_time'      => 'required|date_format:H:i',
                'end_time'        => 'required|date_format:H:i',
                'style'           => 'required|string|in:Hijab,HairDo',

                // Tema utama
                'tema_id'   => 'nullable|exists:tema_baju,id',
                'tema_nama' => 'nullable|string|max:100',
                'tema_kode' => 'nullable|string|max:100',

                // Tema tambahan dari addon kategori 2
                'tema2_id'   => 'nullable|exists:tema_baju,id',
                'tema2_nama' => 'nullable|string|max:100',
                'tema2_kode' => 'nullable|string|max:100',

                'wedding_date' => 'nullable|date|after_or_equal:today',
                'notes'        => 'nullable|string',

                // Step 3
                'ig_cpp'      => 'nullable|string|max:100',
                'ig_cpw'      => 'nullable|string|max:100',
                'tiktok_cpp'  => 'nullable|string|max:100',
                'tiktok_cpw'  => 'nullable|string|max:100',
                'sosmed_lain' => 'nullable|string', // JSON string (optional)

                // ADDON (id)
                'addons'   => 'nullable|array',
                'addons.*' => 'integer|exists:addons,id',

                // Extra slot dari addon kategori 1
                'extra_slot_code'       => 'nullable|string|max:10',
                'extra_photoshoot_slot' => 'nullable|string|max:20',
                'extra_start_time'      => 'nullable|date_format:H:i',
                'extra_end_time'        => 'nullable|date_format:H:i',
                'extra_minutes'         => 'nullable|integer|min:0',
            ]);

            // Normalisasi jam utama (H:i)
            $start = substr($v['start_time'], 0, 5);
            $end   = substr($v['end_time'],   0, 5);

            $kapasitasPerSlot = 2;

            // ==============================
            // 1. Cek kapasitas slot utama (2 studio)
            // ==============================
            $jumlahOverlap = BookingClient::whereDate('photoshoot_date', $v['photoshoot_date'])
                ->where(function ($q) use ($start, $end) {
                    $q->where(function ($q1) use ($start, $end) {
                        // overlap dengan slot utama booking lain
                        $q1->whereTime('start_time', '<', $end)
                        ->whereTime('end_time',   '>', $start);
                    })->orWhere(function ($q2) use ($start, $end) {
                        // overlap dengan extra slot booking lain
                        $q2->whereNotNull('extra_start_time')
                        ->whereNotNull('extra_end_time')
                        ->whereTime('extra_start_time', '<', $end)
                        ->whereTime('extra_end_time',   '>', $start);
                    });
                })
                ->count();

            if ($jumlahOverlap >= $kapasitasPerSlot) {
                return back()
                    ->withErrors(['slot_code' => 'Slot ini sudah penuh. Silakan pilih jam lain.'])
                    ->withInput()
                    ->with('wizard_step', 2);
            }

            // ==============================
            // 2. Cek kode tema utama tidak double di jam sama
            // ==============================
            $temaKode = $v['tema_kode'] ?? null;

            if ($temaKode) {
                $temaDipakai = BookingClient::whereDate('photoshoot_date', $v['photoshoot_date'])
                    ->where(function ($q) use ($temaKode) {
                        $q->where('tema_kode', $temaKode)
                        ->orWhere('tema2_kode', $temaKode);
                    })
                    ->where(function ($q) use ($start, $end) {
                        $q->whereTime('start_time', '<', $end)
                        ->whereTime('end_time',   '>', $start);
                    })
                    ->exists();

                if ($temaDipakai) {
                    return back()
                        ->withErrors([
                            'tema_kode' => 'Kode baju ini sudah dipakai pasangan lain di jam tersebut. Silakan pilih kode lain.'
                        ])
                        ->withInput()
                        ->with('wizard_step', 2);
                }
            }

            // ==============================
            // 3. Proses ADDON
            // ==============================
            $addonIds = $request->input('addons', []);
            $addons   = empty($addonIds)
                ? collect()
                : Addon::whereIn('id', $addonIds)->where('is_active', true)->get();

            $addonSlot = $addons->firstWhere('kategori', 1); // kategori 1 = extra slot waktu
            $addonTema = $addons->firstWhere('kategori', 2); // kategori 2 = tema tambahan

            // ---- 3a. Validasi addon kategori 1 (slot tambahan) ----
            $extraSlotCode       = $v['extra_slot_code']       ?? null;
            $extraSlotLabel      = $v['extra_photoshoot_slot'] ?? null;
            $extraStartRaw       = $v['extra_start_time']      ?? null;
            $extraEndRaw         = $v['extra_end_time']        ?? null;
            $extraMinutesRequest = (int) ($v['extra_minutes']  ?? 0);
            $extraMinutes        = 0;

            if ($addonSlot) {
                if (
                    empty($extraSlotCode) ||
                    empty($extraSlotLabel) ||
                    empty($extraStartRaw) ||
                    empty($extraEndRaw)
                ) {
                    return back()
                        ->withErrors(['extra_slot_code' => 'Addon "'.$addonSlot->nama.'" dipilih. Silakan pilih slot tambahan.'])
                        ->withInput()
                        ->with('wizard_step', 2);
                }

                $extraStart = substr($extraStartRaw, 0, 5);
                $extraEnd   = substr($extraEndRaw,   0, 5);

                // Extra slot *tidak boleh* overlap jam slot utama booking ini
                if ($extraStart < $end && $extraEnd > $start) {
                    return back()
                        ->withErrors(['extra_slot_code' => 'Slot tambahan tidak boleh berada di dalam jam slot utama.'])
                        ->withInput()
                        ->with('wizard_step', 2);
                }

                // Kapasitas utk slot tambahan (dibandingkan main & extra booking lain)
                $jumlahOverlapExtra = BookingClient::whereDate('photoshoot_date', $v['photoshoot_date'])
                    ->where(function ($q) use ($extraStart, $extraEnd) {
                        $q->where(function ($q1) use ($extraStart, $extraEnd) {
                            $q1->whereTime('start_time', '<', $extraEnd)
                            ->whereTime('end_time',   '>', $extraStart);
                        })->orWhere(function ($q2) use ($extraStart, $extraEnd) {
                            $q2->whereNotNull('extra_start_time')
                            ->whereNotNull('extra_end_time')
                            ->whereTime('extra_start_time', '<', $extraEnd)
                            ->whereTime('extra_end_time',   '>', $extraStart);
                        });
                    })
                    ->count();

                if ($jumlahOverlapExtra >= $kapasitasPerSlot) {
                    return back()
                        ->withErrors(['extra_slot_code' => 'Slot tambahan yang dipilih sudah penuh. Silakan pilih jam lain.'])
                        ->withInput()
                        ->with('wizard_step', 2);
                }

                // Hitung extra_minutes (prioritas: durasi di addon, fallback dari request)
                $extraMinutes = (int) ($addonSlot->durasi ?? 0);
                if ($extraMinutes <= 0 && $extraMinutesRequest > 0) {
                    $extraMinutes = $extraMinutesRequest;
                }
            } else {
                // kalau addon slot TIDAK dipilih, kosongkan field extra_*
                $extraSlotCode       = null;
                $extraSlotLabel      = null;
                $extraStartRaw       = null;
                $extraEndRaw         = null;
                $extraMinutes        = 0;
            }

            // ---- 3b. Validasi addon kategori 2 (tema tambahan) ----
            $tema2Id   = $v['tema2_id']   ?? null;
            $tema2Nama = $v['tema2_nama'] ?? null;
            $tema2Kode = $v['tema2_kode'] ?? null;

            if ($addonTema) {
                if (empty($tema2Kode)) {
                    return back()
                        ->withErrors(['tema2_kode' => 'Addon "'.$addonTema->nama.'" dipilih. Silakan pilih tema baju tambahan.'])
                        ->withInput()
                        ->with('wizard_step', 2);
                }

                if (!empty($temaKode) && $tema2Kode === $temaKode) {
                    return back()
                        ->withErrors(['tema2_kode' => 'Tema tambahan tidak boleh sama dengan tema utama.'])
                        ->withInput()
                        ->with('wizard_step', 2);
                }

                if ($tema2Kode) {
                    $tema2Dipakai = BookingClient::whereDate('photoshoot_date', $v['photoshoot_date'])
                        ->where(function ($q) use ($tema2Kode) {
                            $q->where('tema_kode', $tema2Kode)
                            ->orWhere('tema2_kode', $tema2Kode);
                        })
                        ->where(function ($q) use ($start, $end) {
                            $q->whereTime('start_time', '<', $end)
                            ->whereTime('end_time',   '>', $start);
                        })
                        ->exists();

                    if ($tema2Dipakai) {
                        return back()
                            ->withErrors([
                                'tema2_kode' => 'Tema tambahan ini sudah dipakai pasangan lain di jam tersebut. Silakan pilih kode lain.'
                            ])
                            ->withInput()
                            ->with('wizard_step', 2);
                    }
                }
            } else {
                // kalau addon tema TIDAK dipilih, kosongkan field tema2_*
                $tema2Id   = null;
                $tema2Nama = null;
                $tema2Kode = null;
            }

            // ==============================
            // 4. Hitung harga paket & addon
            // ==============================
            $package      = Package::findOrFail($v['package_id']);
            $packagePrice = (int) $package->final_price;
            $addonsTotal  = (int) $addons->sum('harga');
            $grandTotal   = $packagePrice + $addonsTotal;

            // ==============================
            // 5. Generate kode pesanan
            // ==============================
            $kode = 'SP' . now()->format('YmdHis') . Str::upper(Str::random(4));

            $emailGab = trim(($v['email_cpp'] ?? '') . ' & ' . ($v['email_cpw'] ?? ''), ' &');
            if (!$emailGab && Auth::check()) {
                $emailGab = Auth::user()->email;
            }

            $notes = $v['notes'] ?? '';

            if ($addonSlot && !empty($extraSlotLabel)) {
                $notes .= "\n[Addon Slot Waktu: {$addonSlot->nama}, {$extraSlotLabel} ({$extraSlotCode})]";
            }

            if ($addonTema && !empty($tema2Nama)) {
                $labelTema2 = $tema2Nama;
                if (!empty($tema2Kode)) {
                    $labelTema2 .= " ({$tema2Kode})";
                }
                $notes .= "\n[Addon Tema Baju: {$addonTema->nama}, {$labelTema2}]";
            }

            $notes = trim($notes);

            DB::beginTransaction();
            try {
                $booking = BookingClient::create([
                    'user_id'         => optional(Auth::user())->id,

                    'nama_cpp'        => $v['nama_cpp'],
                    'email_cpp'       => $v['email_cpp'] ?? null,
                    'phone_cpp'       => $v['phone_cpp'],
                    'alamat_cpp'      => $v['alamat_cpp'] ?? null,

                    'nama_cpw'        => $v['nama_cpw'],
                    'email_cpw'       => $v['email_cpw'] ?? null,
                    'phone_cpw'       => $v['phone_cpw'],
                    'alamat_cpw'      => $v['alamat_cpw'] ?? null,

                    'ig_cpp'          => $v['ig_cpp'] ?? null,
                    'ig_cpw'          => $v['ig_cpw'] ?? null,
                    'tiktok_cpp'      => $v['tiktok_cpp'] ?? null,
                    'tiktok_cpw'      => $v['tiktok_cpw'] ?? null,
                    'sosmed_lain'     => !empty($v['sosmed_lain'])
                                            ? json_decode($v['sosmed_lain'], true)
                                            : null,

                    'package_id'      => (int) $v['package_id'],
                    'package_price'   => $packagePrice,
                    'addons_total'    => $addonsTotal,
                    'grand_total'     => $grandTotal,

                    'photoshoot_date' => $v['photoshoot_date'],
                    'slot_code'       => $v['slot_code'],
                    'photoshoot_slot' => $v['photoshoot_slot'],
                    'start_time'      => $v['start_time'],
                    'end_time'        => $v['end_time'],

                    // Extra slot
                    'extra_slot_code'       => $extraSlotCode,
                    'extra_photoshoot_slot' => $extraSlotLabel,
                    'extra_start_time'      => $extraStartRaw,
                    'extra_end_time'        => $extraEndRaw,
                    'extra_minutes'         => $extraMinutes,

                    // Tema utama
                    'tema_id'         => $v['tema_id']   ?? null,
                    'tema_nama'       => $v['tema_nama'] ?? null,
                    'tema_kode'       => $temaKode,

                    // Tema tambahan
                    'tema2_id'        => $tema2Id,
                    'tema2_nama'      => $tema2Nama,
                    'tema2_kode'      => $tema2Kode,

                    'style'           => $v['style'],
                    'wedding_date'    => $v['wedding_date'] ?? null,
                    'notes'           => $notes ?: null,

                    'nama_gabungan'   => $v['nama_cpp'] . ' & ' . $v['nama_cpw'],
                    'email_gabungan'  => $emailGab ?: null,
                    'phone_gabungan'  => $v['phone_cpp'] . ' & ' . $v['phone_cpw'],

                    'kode_pesanan'    => $kode,
                    'status'          => 'submitted',
                ]);

                DB::commit();
            } catch (QueryException $e) {
                DB::rollBack();
                return back()
                    ->withErrors('Gagal menyimpan booking. Coba lagi.')
                    ->withInput();
            }

            return redirect()->back()
                ->with('success', 'Booking berhasil! Kode: ' . $kode)
                ->with('anchor', 'Booking');
        }
    
    
    public function storeTemaBaju(Request $request)
        {
            $request->validate([
                'nama'       => 'required|string|max:255',
                'images.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'detail'     => 'required|string',
                'designer'   => 'required|string|max:255',
                'harga'      => 'required|numeric',
                'kode'       => 'required|string|max:50|unique:tema_baju,kode',
                'ukuran'     => 'required|string|max:100',
                'tipe'       => 'required|string|max:50',
            ]);

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('tema_baju', 'public'); // storage/app/public/tema_baju
                    $imagePaths[] = $path;
                }
            }

            TemaBaju::create([
                'nama'     => $request->nama,
                'images'   => json_encode($imagePaths),
                'detail'   => $request->detail,
                'designer' => $request->designer,
                'harga'    => $request->harga,
                'kode'     => $request->kode,
                'ukuran'   => $request->ukuran,
                'tipe'     => $request->tipe,
                // 'order' & 'active' pakai default dari migration
            ]);

            return redirect()
                ->route('executive.catalogue')
                ->with('success', 'Tema baju berhasil ditambahkan.');
        }

        public function updateTemaBaju(Request $request, TemaBaju $temaBaju)
        {
            $request->validate([
                'nama'       => 'required|string|max:255',
                'images.*'   => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'detail'     => 'required|string',
                'designer'   => 'required|string|max:255',
                'harga'      => 'required|numeric',
                'kode'       => 'required|string|max:50|unique:tema_baju,kode,' . $temaBaju->id,
                'ukuran'     => 'required|string|max:100',
                'tipe'       => 'required|string|max:50',
            ]);

            $imagePaths = $temaBaju->images_array; // accessor dari model

            if ($request->hasFile('images')) {
                // hapus gambar lama
                foreach ($imagePaths as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }

                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('tema_baju', 'public');
                    $imagePaths[] = $path;
                }
            }

            $temaBaju->update([
                'nama'     => $request->nama,
                'images'   => json_encode($imagePaths),
                'detail'   => $request->detail,
                'designer' => $request->designer,
                'harga'    => $request->harga,
                'kode'     => $request->kode,
                'ukuran'   => $request->ukuran,
                'tipe'     => $request->tipe,
            ]);

            return redirect()
                ->route('executive.catalogue')
                ->with('success', 'Tema baju berhasil diperbarui.');
        }

        public function destroyTemaBaju(TemaBaju $temaBaju)
            {
                $imagePaths = $temaBaju->images_array ?? [];

                foreach ($imagePaths as $image) {
                    Storage::disk('public')->delete($image);
                }

                $temaBaju->delete();

                return redirect()
                    ->route('executive.catalogue')
                    ->with('success', 'Tema baju berhasil dihapus.');
            }

        public function storePackage(Request $request)
            {
                $request->validate([
                    'nama_paket' => 'required|string|max:255',
                    'deskripsi'  => 'nullable|string',
                    'harga'      => 'required|numeric',
                    'durasi'     => 'nullable|integer',
                    'discount'   => 'nullable|numeric|min:0|max:100',
                    'notes'      => 'nullable|string',
                    'konsep'     => 'nullable|string',
                    'rules'      => 'nullable|string',
                    'images'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                ]);

                $imagePath = null;
                if ($request->hasFile('images')) {
                    // simpan ke storage/app/public/packages
                    $imagePath = $request->file('images')->store('packages', 'public');
                }

                $maxOrder = Package::max('order') ?? 0;

                Package::create([
                    'nama_paket' => $request->nama_paket,
                    'deskripsi'  => $request->deskripsi,
                    'harga'      => $request->harga,
                    'durasi'     => $request->durasi,
                    'discount'   => $request->discount ?? 0,
                    'notes'      => $request->notes,
                    'konsep'     => $request->konsep,
                    'rules'      => $request->rules,
                    'images'     => $imagePath,
                    'order'      => $maxOrder + 1,
                    // 'active' pakai default dari migration
                ]);

                return redirect()
                    ->route('executive.catalogue')
                    ->with('success', 'Package berhasil ditambahkan.');
            }

        public function updatePackage(Request $request, Package $package)
            {
                $request->validate([
                    'nama_paket' => 'required|string|max:255',
                    'deskripsi'  => 'nullable|string',
                    'harga'      => 'required|numeric',
                    'durasi'     => 'nullable|integer',
                    'discount'   => 'nullable|numeric|min:0|max:100',
                    'notes'      => 'nullable|string',
                    'konsep'     => 'nullable|string',
                    'rules'      => 'nullable|string',
                    'images'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                ]);

                $imagePath = $package->images;

                // kalau ada upload gambar baru => hapus lama, simpan baru
                if ($request->hasFile('images')) {
                    if ($imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                    $imagePath = $request->file('images')->store('packages', 'public');
                }

                $package->update([
                    'nama_paket' => $request->nama_paket,
                    'deskripsi'  => $request->deskripsi,
                    'harga'      => $request->harga,
                    'durasi'     => $request->durasi,
                    'discount'   => $request->discount ?? 0,
                    'notes'      => $request->notes,
                    'konsep'     => $request->konsep,
                    'rules'      => $request->rules,
                    'images'     => $imagePath,
                    // 'order' & 'active' kalau mau diubah, bisa ditambah di sini
                ]);

                return redirect()
                    ->route('executive.catalogue')
                    ->with('success', 'Package berhasil diperbarui.');
            }

        public function destroyPackage(Package $package)
            {
                // hapus file gambar di storage, kalau ada
                if ($package->images) {
                    Storage::disk('public')->delete($package->images);
                }

                $package->delete();

                return redirect()
                    ->route('executive.catalogue')
                    ->with('success', 'Package berhasil dihapus.');
            }
    
    private function loadPage(Request $request, $page)
        {
            if ($request->ajax()) {
                return $this->loadContent($request, $page);
            }
            if ($page === 'DataAkun') {
                $users = User::with(['dataDiri', 'dataDiriKaryawan'])->get();
                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page' => $page,
                    'users' => $users,
                ]);
            }
            if ($page === 'Catalogue') {
                $packages = Package::orderBy('order')->get();
                $temas    = TemaBaju::orderBy('order')->get();
                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page' => $page,
                    'packages' => $packages,
                    'temas' => $temas,
                ]);
            }
            if ($page === 'MenuPanel.HomePages.Dashboard') {
                $slides = HeroSlide::orderBy('order')->get();
                $marquees = Marquee::orderBy('order')->get();
                $aboutus = AboutUs::active()->orderBy('order')->get();
                $model1 = AboutUs::active()->where('model_type', 'model1')->orderBy('order')->get();
                $model2 = AboutUs::active()->where('model_type', 'model2')->orderBy('order')->get();
                $model3 = AboutUs::active()->where('model_type', 'model3')->orderBy('order')->get();
                $reviews = Review::where('active',1)->latest()->get();
                $heroes  = HeroContent::where('active',1)->orderBy('order')->get();
                $faqs    = Faq::where('active',1)->orderBy('order')->get();
                $services = Service::orderBy('order')->get();

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page' => $page,
                    'slides' => $slides,
                    'marquees' => $marquees,
                    'aboutus'   => $aboutus,
                    'model1'   => $model1,
                    'model2'   => $model2,
                    'model3'   => $model3,
                    'reviews'   => $reviews,
                    'heroes'   => $heroes,
                    'faqs'   => $faqs,
                    'services'  => $services,
                ]);
            }
            if ($page === 'MenuPanel.HomePages.Portofolio') {
                $slides = HeroSlide::orderBy('order')->get();
                $galleries= GalleryItem::where('active',1)->orderBy('order')->get();

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page' => $page,
                    'slides' => $slides,
                    'galleries' => $galleries,
                ]);
            }
            if ($page === 'MenuPanel.HomePages.Pricelist') {
                $slides = HeroSlide::orderBy('order')->get();
                $promos  = PromoBanner::where('active',1)->orderBy('order')->get();
                $addons   = Addon::orderBy('kategori')->orderBy('nama')->get();

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page' => $page,
                    'slides' => $slides,
                    'promos' => $promos,
                    'addons'   => $addons,
                ]);
            }
            if ($page === 'JadwalPesanan') {
                // Ambil input tanggal, status, dan search dari request
                $selectedDate = $request->input('date', now()->toDateString());
                $status       = $request->input('status', 'all');
                $search       = $request->input('search');

                // Query untuk mengambil data booking berdasarkan tanggal yang dipilih
                $query = BookingClient::whereDate('photoshoot_date', $selectedDate);
                $query->where('kode_pesanan', 'like', 'SP%');

                // Mapping status
                $statusMap = [
                    'pending'   => 'submitted',
                    'confirmed' => 'confirmed',
                    'canceled'  => 'cancelled',
                    'completed' => 'completed',
                ];

                // Filter berdasarkan status
                if ($status !== 'all' && isset($statusMap[$status])) {
                    $query->where('status', $statusMap[$status]);
                }

                // Filter berdasarkan pencarian
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('kode_pesanan', 'like', "%{$search}%")
                        ->orWhere('nama_gabungan', 'like', "%{$search}%")
                        ->orWhere('nama_cpp', 'like', "%{$search}%")
                        ->orWhere('nama_cpw', 'like', "%{$search}%")
                        ->orWhere('phone_gabungan', 'like', "%{$search}%")
                        ->orWhere('phone_cpp', 'like', "%{$search}%")
                        ->orWhere('phone_cpw', 'like', "%{$search}%");
                    });
                }

                // Ambil data booking yang sudah difilter
                $bookings = $query->orderBy('start_time')->get();

                // Ambil data tambahan seperti packages, addons, temas
                $packages = Package::orderBy('order')->get();
                $addons   = Addon::where('is_active', true)->orderBy('kategori')->orderBy('nama')->get();
                $temas    = TemaBaju::orderBy('order')->get();
                $addonGroups = $addons->groupBy('kategori');

                // Menggunakan loadPage untuk memuat halaman utama dengan data yang sudah disiapkan
                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page'         => $page,
                    'bookings'     => $bookings,
                    'selectedDate' => $selectedDate,
                    'status'       => $status,
                    'search'       => $search,
                    'packages'     => $packages,
                    'addons'       => $addons,
                    'temas'        => $temas,
                    'addonGroups' => $addonGroups,
                ]);
            }
            if ($page === 'JadwalKerja') {
                $weekOffset = (int) $request->input('week', 0);

                $startOfWeek = now()
                    ->startOfWeek()
                    ->addWeeks($weekOffset);

                $endOfWeek = $startOfWeek->copy()->addDays(6);

                $bookings = BookingClient::with([
                    'skemaKerja.editor',
                    'skemaKerja.fotografer',
                    'skemaKerja.videografer',
                    'skemaKerja.makeup',
                    'skemaKerja.attire',
                ])
                ->whereBetween('photoshoot_date', [
                    $startOfWeek->toDateString(),
                    $endOfWeek->toDateString(),
                ])
                ->orderBy('photoshoot_date')
                ->orderBy('start_time')
                ->get();

                foreach ($bookings as $booking) {
                    $booking->skemaKerja()->firstOrCreate([
                        'booking_client_id' => $booking->id,
                    ]);
                }

                $bookingsByDate = $bookings->groupBy(fn ($b) =>
                    $b->photoshoot_date->format('Y-m-d')
                );

                $karyawanByRole = [
                    'editor' => DataDiriKaryawan::where('role', 'EDITOR')->get(),
                    'photografer' => DataDiriKaryawan::where('role', 'PHOTOGRAFER')->get(),
                    'videografer' => DataDiriKaryawan::where('role', 'VIDEOGRAFER')->get(),
                    'makeup' => DataDiriKaryawan::where('role', 'MAKE_UP')->get(),
                    'attire' => DataDiriKaryawan::where('role', 'ATTIRE')->get(),
                ];

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page'           => $page,
                    'startOfWeek'    => $startOfWeek,
                    'bookingsByDate' => $bookingsByDate,
                    'karyawanByRole' => $karyawanByRole,
                ]);
            }
            if ($page === 'StatistikContent.StatistikSurvey') {

                $services = [
                    'Fotografer',
                    'Videografer',
                    'MUA',
                    'Admin Studio',
                    'Attire ( Busana )',
                    'Tim Fitting',
                    'Admin Attire'
                ];

                $futureServices = [
                    'Post Wedding',
                    'Maternity',
                    'Family Portrait',
                    'Anniversary Session'
                ];

                $search = $request->input('search');
                $scoreFilter = $request->input('score');
                $serviceFilter = $request->input('service');
                $futureFilter = $request->input('future_service');
                $createdMonth = $request->input('created_month');
                $photoMonth = $request->input('photo_month');
                $perPage = 20;
                $query = Survey::query();
                if (!empty($search)) {

                    $query->where(
                        'customer_name',
                        'like',
                        "%{$search}%"
                    );

                }
                if (!empty($scoreFilter)) {

                    $query->where(
                        'recommendation_score',
                        $scoreFilter
                    );

                }

                if (!empty($serviceFilter)) {

                    $query->whereJsonContains(
                        'favorite_services',
                        $serviceFilter
                    );

                }

                if (!empty($futureFilter)) {

                    $query->whereJsonContains(
                        'future_services',
                        $futureFilter
                    );

                }

                if (!empty($createdMonth)) {

                    $query->whereMonth(
                        'created_at',
                        $createdMonth
                    );

                }

                if (!empty($photoMonth)) {

                    $query->whereMonth(
                        'photo_date',
                        $photoMonth
                    );

                }

                $query->latest();
                $allDataRaw = $query->get();
                $allData = $allDataRaw->unique(function ($item) {

                    return strtolower(trim($item->customer_name))
                        . '_'
                        . $item->photo_date
                        . '_'
                        . $item->recommendation_score;

                });

                $duplicateCount = $allDataRaw->count() - $allData->count();
                $dataPageBefore = $query
                    ->paginate($perPage)
                    ->withQueryString();
                $afterCollection = $allData
                    ->sortByDesc('created_at')
                    ->values();

                $currentPageAfter = request()->get(
                    'after_page',
                    1
                );

                $currentItemsAfter = $afterCollection->slice(
                    ($currentPageAfter - 1) * $perPage,
                    $perPage
                )->values();

                $dataPageAfter = new \Illuminate\Pagination\LengthAwarePaginator(
                    $currentItemsAfter,
                    $afterCollection->count(),
                    $perPage,
                    $currentPageAfter,
                    [
                        'path' => request()->url(),
                        'query' => request()->query(),
                        'pageName' => 'after_page',
                    ]
                );

                $favoriteBefore = [];

                foreach ($services as $service) {
                    $favoriteBefore[$service] = 0;
                }

                foreach ($allDataRaw as $survey) {

                    if (is_array($survey->favorite_services)) {

                        foreach ($survey->favorite_services as $service) {

                            if (isset($favoriteBefore[$service])) {
                                $favoriteBefore[$service]++;
                            }

                        }

                    }

                }
                $favoriteAfter = [];

                foreach ($services as $service) {
                    $favoriteAfter[$service] = 0;
                }

                foreach ($allData as $survey) {

                    if (is_array($survey->favorite_services)) {

                        foreach ($survey->favorite_services as $service) {

                            if (isset($favoriteAfter[$service])) {
                                $favoriteAfter[$service]++;
                            }

                        }

                    }

                }

                $futureBefore = [];
                foreach ($futureServices as $service) {
                    $futureBefore[$service] = 0;
                }

                foreach ($allDataRaw as $survey) {
                    if (is_array($survey->future_services)) {
                        foreach ($survey->future_services as $service) {
                            if (isset($futureBefore[$service])) {
                                $futureBefore[$service]++;
                            }
                        }
                    }
                }

                $futureAfter = [];

                foreach ($futureServices as $service) {
                    $futureAfter[$service] = 0;
                }

                foreach ($allData as $survey) {
                    if (is_array($survey->future_services)) {
                        foreach ($survey->future_services as $service) {
                            if (isset($futureAfter[$service])) {
                                $futureAfter[$service]++;
                            }
                        }
                    }
                }

                $scoreDistributionBefore = [];
                $scoreDistributionAfter = [];

                for ($i = 1; $i <= 10; $i++) {

                    $scoreDistributionBefore[$i] = $allDataRaw
                        ->where('recommendation_score', $i)
                        ->count();

                    $scoreDistributionAfter[$i] = $allData
                        ->where('recommendation_score', $i)
                        ->count();

                }

                $statsBefore = [

                    'total' => $allDataRaw->count(),

                    'duplikat' => $duplicateCount,

                    'nama_kosong' => $allDataRaw
                        ->whereNull('customer_name')
                        ->count(),

                    'tanggal_kosong' => $allDataRaw
                        ->whereNull('photo_date')
                        ->count(),

                    'feedback_kosong' => $allDataRaw
                        ->whereNull('feedback')
                        ->count(),

                    'favorite_kosong' => $allDataRaw
                        ->filter(fn($item) => empty($item->favorite_services))
                        ->count(),

                    'rata_score' => round(
                        $allDataRaw->avg('recommendation_score'),
                        2
                    ),

                ];

                $statsAfter = [

                    'total' => $allData->count(),
                    'duplikat' => 0,
                    'nama_kosong' => 0,
                    'tanggal_kosong' => 0,
                    'feedback_kosong' => 0,
                    'favorite_kosong' => 0,
                    'rata_score' => round(
                        $allData->avg('recommendation_score'),
                        2
                    ),

                ];

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page' => $page,
                    'services' => $services,
                    'favoriteBefore' => $favoriteBefore,
                    'favoriteAfter' => $favoriteAfter,
                    'futureServices' => $futureServices,
                    'futureBefore' => $futureBefore,
                    'futureAfter' => $futureAfter,
                    'scoreDistributionBefore' => $scoreDistributionBefore,
                    'scoreDistributionAfter' => $scoreDistributionAfter,
                    'statsBefore' => $statsBefore,
                    'statsAfter' => $statsAfter,
                    'surveys' => $allDataRaw,
                    'dataPageBefore' => $dataPageBefore,
                    'dataPageAfter' => $dataPageAfter,
                    'search' => $search,
                    'scoreFilter' => $scoreFilter,
                    'serviceFilter' => $serviceFilter,
                    'createdMonth' => $createdMonth,
                    'photoMonth' => $photoMonth,

                ]);
            }
            if ($page === 'StatistikContent.StatistikReview') {
                $sort = $request->sort;

                $googleReviews = GoogleReview::query();

                if ($sort == '5star') {

                    $googleReviews
                        ->where('rating', 5)
                        ->orderBy('review_date', 'desc');

                } elseif ($sort == '4star') {

                    $googleReviews
                        ->where('rating', 4)
                        ->orderBy('review_date', 'desc');

                } elseif ($sort == 'oldest') {

                    $googleReviews
                        ->orderBy('review_date', 'asc');

                } else {

                    $googleReviews
                        ->orderBy('review_date', 'desc');
                }

                $googleReviews = $googleReviews
                    ->take(300)
                    ->get();

                return view(
                    'OPERATIONALPAGES.PAGE.EXECUTIVE',
                    [
                        'page' => $page,
                        'googleReviews' => $googleReviews,
                        'sort' => $sort,
                    ]
                );
            }
            if ($page === 'Brand.KategoriPartnership') {
                $brandCategories = BrandCategory::latest()->get();
                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page' => $page,
                    'brandCategories' => $brandCategories,
                ]);
            }
            return view('OPERATIONALPAGES.PAGE.EXECUTIVE', ['page' => $page]);
        }
    public function loadContent(Request $request, $page)
        {
            if ($page === 'DataAkun') {
                $users = User::with(['dataDiri', 'dataDiriKaryawan'])->get();
                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$page", compact('users'));
            }
            if ($page === 'Catalogue') {
                $packages = Package::orderBy('order')->get();
                $temas    = TemaBaju::orderBy('order')->get();
                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$page", compact('packages', 'temas'));
            }
            if ($page === 'MenuPanel.HomePages.Dashboard') {
                $slides = $this->applyFilters(
                    HeroSlide::query(),
                    ['title', 'subtitle'],
                    $request->input('slides_q'),
                    $request->input('slides_status', 'all'),
                    $request->input('slides_sort', 'order_asc'),
                    'order',
                    'title'
                )->get();

                $marquees = $this->applyFilters(
                    Marquee::query(),
                    ['text','icon_class'],
                    $request->input('marq_q'),
                    $request->input('marq_status', 'all'),
                    $request->input('marq_sort', 'order_asc'),
                    'order',
                    'text'
                )->get();

                $aboutus = $this->applyFilters(
                    AboutUs::query(),
                    ['title', 'subtitle', 'description', 'model_type'],
                    $request->input('about_q'),
                    $request->input('about_status', 'all'),
                    $request->input('about_sort', 'order_asc'),
                    'order',
                    'title'
                )->get();

                $model1 = $aboutus->where('model_type', 'model1');
                $model2 = $aboutus->where('model_type', 'model2');
                $model3 = $aboutus->where('model_type', 'model3');

                $reviews = $this->applyFilters(
                    Review::query(),
                    ['name','role','content','date'],
                    $request->input('rev_q'),
                    $request->input('rev_status', 'all'),
                    $request->input('rev_sort', 'created_desc'),
                    'created_at',
                    'name'
                )->get();
                $heroes = $this->applyFilters(
                    HeroContent::query(),
                    [],
                    null,
                    $request->input('hero_status', 'all'),
                    $request->input('hero_sort', 'order_asc'),
                    'order',
                    null
                )->get();
                $faqs = $this->applyFilters(
                    Faq::query(),
                    ['question','answer'],
                    $request->input('faq_q'),
                    $request->input('faq_status', 'all'),
                    $request->input('faq_sort', 'order_asc'),
                    'order',
                    'question'
                )->get();
                $services = $this->applyFilters(
                    Service::query(),
                    ['title','description'],
                    $request->input('svc_q'),
                    $request->input('svc_status', 'all'),
                    $request->input('svc_sort', 'order_asc'),
                    'order',
                    'title'
                )->get();

                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$page", compact('slides', 'marquees', 'aboutus','model1', 'model2', 'model3', 'reviews', 'heroes','faqs','services'));
            }
            if ($page === 'MenuPanel.HomePages.Portofolio') {
                $slides = $this->applyFilters(
                    HeroSlide::query(),
                    ['title', 'subtitle'],
                    $request->input('slides_q'),
                    $request->input('slides_status', 'all'),
                    $request->input('slides_sort', 'order_asc'),
                    'order',
                    'title'
                )->get();
                
                $galleries = $this->applyFilters(
                    GalleryItem::query(),
                    ['title','description','category'],
                    $request->input('gal_q'),
                    $request->input('gal_status', 'all'),
                    $request->input('gal_sort', 'order_asc'),
                    'order',
                    'title'
                )->get();

                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$page", compact('slides', 'galleries',));
            }
            if ($page === 'MenuPanel.HomePages.Pricelist') {
                $slides = $this->applyFilters(
                    HeroSlide::query(),
                    ['title', 'subtitle'],
                    $request->input('slides_q'),
                    $request->input('slides_status', 'all'),
                    $request->input('slides_sort', 'order_asc'),
                    'order',
                    'title'
                )->get();
                    $promos = $this->applyFilters(
                    PromoBanner::query(),
                    [],
                    null,
                    $request->input('promo_status', 'all'),
                    $request->input('promo_sort', 'order_asc'),
                    'order',
                    null
                )->get();
                $addons   = Addon::orderBy('kategori')->orderBy('nama')->get();
                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$page", compact('slides', 'promos','addons'));
            }
            if ($page === 'JadwalPesanan') {
                // Ambil input tanggal, status, dan search dari request
                $selectedDate = $request->input('date', now()->toDateString());
                $status       = $request->input('status', 'all');
                $search       = $request->input('search');

                // Query untuk mengambil data booking berdasarkan tanggal yang dipilih
                $query = BookingClient::whereDate('photoshoot_date', $selectedDate);

                // Filter khusus untuk executive jika perlu
                $query->where('kode_pesanan', 'like', 'SP%');

                // Mapping status
                $statusMap = [
                    'pending'   => 'submitted',
                    'confirmed' => 'confirmed',
                    'canceled'  => 'cancelled',
                    'completed' => 'completed',
                ];

                // Filter berdasarkan status
                if ($status !== 'all' && isset($statusMap[$status])) {
                    $query->where('status', $statusMap[$status]);
                }

                // Filter berdasarkan pencarian
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('kode_pesanan', 'like', "%{$search}%")
                        ->orWhere('nama_gabungan', 'like', "%{$search}%")
                        ->orWhere('nama_cpp', 'like', "%{$search}%")
                        ->orWhere('nama_cpw', 'like', "%{$search}%")
                        ->orWhere('phone_gabungan', 'like', "%{$search}%")
                        ->orWhere('phone_cpp', 'like', "%{$search}%")
                        ->orWhere('phone_cpw', 'like', "%{$search}%");
                    });
                }

                // Ambil data booking yang sudah difilter
                $bookings = $query->orderBy('start_time')->get();

                // Ambil data lainnya seperti packages, addons, temas
                $packages = Package::orderBy('order')->get();
                $addons   = Addon::where('is_active', true)->orderBy('kategori')->orderBy('nama')->get();
                $temas    = TemaBaju::orderBy('order')->get();

                $addonGroups = $addons->groupBy('kategori');

                // Kirim data ke view menggunakan loadContent
                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$page", compact(
                    'bookings', 'selectedDate', 'status', 'search', 'packages', 'addons', 'temas', 'addonGroups'
                ));
            }
            if ($page === 'JadwalKerja') {

                $weekOffset = (int) $request->input('week', 0);

                $startOfWeek = now()
                    ->startOfWeek()
                    ->addWeeks($weekOffset);

                $endOfWeek = $startOfWeek->copy()->addDays(6);

                $bookings = BookingClient::with([
                    'skemaKerja.editor',
                    'skemaKerja.fotografer',
                    'skemaKerja.videografer',
                    'skemaKerja.makeup',
                    'skemaKerja.attire',
                ])
                ->whereBetween('photoshoot_date', [
                    $startOfWeek->toDateString(),
                    $endOfWeek->toDateString(),
                ])
                ->orderBy('photoshoot_date')
                ->orderBy('start_time')
                ->get();

                foreach ($bookings as $booking) {
                    $booking->skemaKerja()->firstOrCreate([
                        'booking_client_id' => $booking->id,
                    ]);
                }

                $bookingsByDate = $bookings->groupBy(fn ($b) =>
                    $b->photoshoot_date->format('Y-m-d')
                );

                $karyawanByRole = [
                    'editor' => DataDiriKaryawan::where('role', 'EDITOR')->get(),
                    'photografer' => DataDiriKaryawan::where('role', 'PHOTOGRAFER')->get(),
                    'videografer' => DataDiriKaryawan::where('role', 'VIDEOGRAFER')->get(),
                    'makeup' => DataDiriKaryawan::where('role', 'MAKE_UP')->get(),
                    'attire' => DataDiriKaryawan::where('role', 'ATTIRE')->get(),
                ];

                return view(
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$page",
                    compact('startOfWeek', 'bookingsByDate', 'karyawanByRole')
                );
            }
            if ($page === 'StatistikContent.StatistikSurvey') {

                $services = [
                    'Fotografer',
                    'Videografer',
                    'MUA',
                    'Admin Studio',
                    'Attire ( Busana )',
                    'Tim Fitting',
                    'Admin Attire'
                ];

                $futureServices = [
                    'Post Wedding',
                    'Maternity',
                    'Family Portrait',
                    'Anniversary Session'
                ];

                $search = $request->input('search');
                $scoreFilter = $request->input('score');
                $serviceFilter = $request->input('service');
                $futureFilter = $request->input('future_service');
                $createdMonth = $request->input('created_month');
                $photoMonth = $request->input('photo_month');
                $perPage = 20;
                $query = Survey::query();
                if (!empty($search)) {

                    $query->where(
                        'customer_name',
                        'like',
                        "%{$search}%"
                    );

                }
                if (!empty($scoreFilter)) {

                    $query->where(
                        'recommendation_score',
                        $scoreFilter
                    );

                }

                if (!empty($serviceFilter)) {

                    $query->whereJsonContains(
                        'favorite_services',
                        $serviceFilter
                    );

                }

                if (!empty($futureFilter)) {

                    $query->whereJsonContains(
                        'future_services',
                        $futureFilter
                    );

                }

                if (!empty($createdMonth)) {

                    $query->whereMonth(
                        'created_at',
                        $createdMonth
                    );

                }

                if (!empty($photoMonth)) {

                    $query->whereMonth(
                        'photo_date',
                        $photoMonth
                    );

                }

                $query->latest();
                $allDataRaw = $query->get();
                $allData = $allDataRaw->unique(function ($item) {

                    return strtolower(trim($item->customer_name))
                        . '_'
                        . $item->photo_date
                        . '_'
                        . $item->recommendation_score;

                });
                $duplicateCount = $allDataRaw->count() - $allData->count();
                                $dataPageBefore = $query
                    ->paginate($perPage)
                    ->withQueryString();
                $afterCollection = $allData
                    ->sortByDesc('created_at')
                    ->values();

                $currentPageAfter = request()->get(
                    'after_page',
                    1
                );

                $currentItemsAfter = $afterCollection->slice(
                    ($currentPageAfter - 1) * $perPage,
                    $perPage
                )->values();

                $dataPageAfter = new \Illuminate\Pagination\LengthAwarePaginator(
                    $currentItemsAfter,
                    $afterCollection->count(),
                    $perPage,
                    $currentPageAfter,
                    [
                        'path' => request()->url(),
                        'query' => request()->query(),
                        'pageName' => 'after_page',
                    ]
                );

                $favoriteBefore = [];
                foreach ($services as $service) {
                    $favoriteBefore[$service] = 0;
                }

                foreach ($allDataRaw as $survey) {

                    if (is_array($survey->favorite_services)) {

                        foreach ($survey->favorite_services as $service) {

                            if (isset($favoriteBefore[$service])) {
                                $favoriteBefore[$service]++;
                            }

                        }

                    }

                }

                $favoriteAfter = [];
                foreach ($services as $service) {
                    $favoriteAfter[$service] = 0;
                }
                foreach ($allData as $survey) {

                    if (is_array($survey->favorite_services)) {

                        foreach ($survey->favorite_services as $service) {

                            if (isset($favoriteAfter[$service])) {
                                $favoriteAfter[$service]++;
                            }

                        }

                    }

                }

                $futureBefore = [];

                foreach ($futureServices as $service) {
                    $futureBefore[$service] = 0;
                }

                foreach ($allDataRaw as $survey) {
                    if (is_array($survey->future_services)) {
                        foreach ($survey->future_services as $service) {
                            if (isset($futureBefore[$service])) {
                                $futureBefore[$service]++;
                            }
                        }
                    }
                }

                $futureAfter = [];

                foreach ($futureServices as $service) {
                    $futureAfter[$service] = 0;
                }

                foreach ($allData as $survey) {
                    if (is_array($survey->future_services)) {
                        foreach ($survey->future_services as $service) {
                            if (isset($futureAfter[$service])) {
                                $futureAfter[$service]++;
                            }
                        }
                    }
                }

                $scoreDistributionBefore = [];
                $scoreDistributionAfter = [];
                for ($i = 1; $i <= 10; $i++) {

                    $scoreDistributionBefore[$i] = $allDataRaw
                        ->where('recommendation_score', $i)
                        ->count();

                    $scoreDistributionAfter[$i] = $allData
                        ->where('recommendation_score', $i)
                        ->count();

                }
                $statsBefore = [

                    'total' => $allDataRaw->count(),
                    'duplikat' => $duplicateCount,
                    'nama_kosong' => $allDataRaw
                        ->whereNull('customer_name')
                        ->count(),
                    'tanggal_kosong' => $allDataRaw
                        ->whereNull('photo_date')
                        ->count(),
                    'feedback_kosong' => $allDataRaw
                        ->whereNull('feedback')
                        ->count(),
                    'favorite_kosong' => $allDataRaw
                        ->filter(fn($item) => empty($item->favorite_services))
                        ->count(),
                    'rata_score' => round(
                        $allDataRaw->avg('recommendation_score'),
                        2
                    ),

                ];

                $statsAfter = [

                    'total' => $allData->count(),
                    'duplikat' => 0,
                    'nama_kosong' => 0,
                    'tanggal_kosong' => 0,
                    'feedback_kosong' => 0,
                    'favorite_kosong' => 0,
                    'rata_score' => round(
                        $allData->avg('recommendation_score'),
                        2
                    ),

                ];

                return view(
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$page",
                    compact(
                        'services',
                        'favoriteBefore',
                        'favoriteAfter',
                        'futureServices',
                        'futureBefore',
                        'futureAfter',
                        'scoreDistributionBefore',
                        'scoreDistributionAfter',
                        'statsBefore',
                        'statsAfter',
                        'surveys',
                        'dataPageBefore',
                        'dataPageAfter',
                        'search',
                        'scoreFilter',
                        'serviceFilter',
                        'createdMonth',
                        'photoMonth'
                    )
                );
            }
            if ($page === 'StatistikContent.StatistikReview') {
                $googleReviews = GoogleReview::latest(
                    'review_date'
                )->get();

                return view(
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$page",
                    compact('googleReviews')
                );
            }
            if ($page === 'StatistikContent.StatistikReview') {
                $sort = $request->sort;

                $googleReviews = GoogleReview::query();

                if ($sort == '5star') {

                    $googleReviews
                        ->where('rating', 5)
                        ->orderBy('review_date', 'desc');

                } elseif ($sort == '4star') {

                    $googleReviews
                        ->where('rating', 4)
                        ->orderBy('review_date', 'desc');

                } elseif ($sort == 'oldest') {

                    $googleReviews
                        ->orderBy('review_date', 'asc');

                } else {

                    $googleReviews
                        ->orderBy('review_date', 'desc');
                }

                $googleReviews = $googleReviews
                    ->take(300)
                    ->get();

                return view(
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$page",
                    compact('googleReviews', 'sort')
                );
            }
            if ($page === 'Brand.KategoriPartnership') {

                $brandCategories = BrandCategory::latest()->get();
                return view(
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$page",
                    compact('brandCategories')
                );
            }
            if (view()->exists("OPERATIONALPAGES.FITUR.MAINCONTENT.$page")) {
                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$page");
            }

            return "<div class='alert alert-warning'>Halaman <b>$page</b> belum dibuat.</div>";
        }
    public function loadDirect(Request $request, $page)
        {
            if (view()->exists("OPERATIONALPAGES.FITUR.MAINCONTENT.$page")) {
                if ($page === 'DataAkun') {
                    $users = User::with(['dataDiri', 'dataDiriKaryawan'])->get();
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page'  => $page,
                        'users' => $users,
                    ]);
                }
                if ($page === 'Catalogue') {
                    $packages = Package::orderBy('order')->get();
                    $temas    = TemaBaju::orderBy('order')->get();
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page'     => $page,
                        'packages' => $packages,
                        'temas'    => $temas,
                    ]);
                }
                if ($page === 'MenuPanel.HomePages.Dashboard') {
                    $slides = $this->applyFilters(
                        HeroSlide::query(),
                        ['title','subtitle'],
                        $request->input('slides_q'),
                        $request->input('slides_status', 'all'),
                        $request->input('slides_sort', 'order_asc'),
                        'order',
                        'title'
                    )->get();
                    $marquees = $this->applyFilters(
                        Marquee::query(),
                        ['text','icon_class'],
                        $request->input('marq_q'),
                        $request->input('marq_status', 'all'),
                        $request->input('marq_sort', 'order_asc'),
                        'order',
                        'text'
                    )->get();
                    $aboutus = $this->applyFilters(
                        AboutUs::query(),
                        ['title', 'subtitle', 'description', 'model_type'],
                        $request->input('about_q'),
                        $request->input('about_status', 'all'),
                        $request->input('about_sort', 'order_asc'),
                        'order',
                        'title'
                    )->get();
                    $model1 = $aboutus->where('model_type', 'model1');
                    $model2 = $aboutus->where('model_type', 'model2');
                    $model3 = $aboutus->where('model_type', 'model3');
                    $reviews = $this->applyFilters(
                        Review::query(),
                        ['name','role','content','date'],
                        $request->input('rev_q'),
                        $request->input('rev_status', 'all'),
                        $request->input('rev_sort', 'created_desc'),
                        'created_at',
                        'name'
                    )->get();
                    $heroes = $this->applyFilters(
                        HeroContent::query(),
                        [],
                        null,
                        $request->input('hero_status', 'all'),
                        $request->input('hero_sort', 'order_asc'),
                        'order',
                        null
                    )->get();
                    $faqs = $this->applyFilters(
                        Faq::query(),
                        ['question','answer'],
                        $request->input('faq_q'),
                        $request->input('faq_status', 'all'),
                        $request->input('faq_sort', 'order_asc'),
                        'order',
                        'question'
                    )->get();
                    $services = $this->applyFilters(
                        Service::query(),
                        ['title','description'],
                        $request->input('svc_q'),
                        $request->input('svc_status', 'all'),
                        $request->input('svc_sort', 'order_asc'),
                        'order',
                        'title'
                    )->get();

                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page'   => $page,
                        'slides'   => $slides,
                        'marquees'   => $marquees,
                        'aboutus'   => $aboutus,
                        'model1'   => $model1,
                        'model2'   => $model2,
                        'model3'   => $model3,
                        'reviews'   => $reviews,
                        'heroes'   => $heroes,
                        'faqs'   => $faqs,
                        'services'  => $services,
                    ]);
                }
                if ($page === 'MenuPanel.HomePages.Portofolio') {
                    $slides = $this->applyFilters(
                        HeroSlide::query(),
                        ['title','subtitle'],
                        $request->input('slides_q'),
                        $request->input('slides_status', 'all'),
                        $request->input('slides_sort', 'order_asc'),
                        'order',
                        'title'
                    )->get();
                    
                    $galleries = $this->applyFilters(
                        GalleryItem::query(),
                        ['title','description','category'],
                        $request->input('gal_q'),
                        $request->input('gal_status', 'all'),
                        $request->input('gal_sort', 'order_asc'),
                        'order',
                        'title'
                    )->get();

                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                        'slides' => $slides,
                        'galleries' => $galleries,
                    ]);
                }
                if ($page === 'MenuPanel.HomePages.Pricelist') {
                    $slides = $this->applyFilters(
                        HeroSlide::query(),
                        ['title','subtitle'],
                        $request->input('slides_q'),
                        $request->input('slides_status', 'all'),
                        $request->input('slides_sort', 'order_asc'),
                        'order',
                        'title'
                    )->get();
                    $promos = $this->applyFilters(
                        PromoBanner::query(),
                        [],
                        null,
                        $request->input('promo_status', 'all'),
                        $request->input('promo_sort', 'order_asc'),
                        'order',
                        null
                    )->get();
                    $addons   = Addon::orderBy('kategori')->orderBy('nama')->get();
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                        'slides' => $slides,
                        'promos' => $promos,
                        'addons'   => $addons,
                    ]);
                }
                if ($page === 'JadwalPesanan') {
                    // Ambil input tanggal, status, dan search dari request
                    $selectedDate = $request->input('date', now()->toDateString());
                    $status       = $request->input('status', 'all');
                    $search       = $request->input('search');

                    // Query untuk mengambil data booking berdasarkan tanggal yang dipilih
                    $query = BookingClient::whereDate('photoshoot_date', $selectedDate);

                    // Filter khusus untuk executive jika perlu
                    $query->where('kode_pesanan', 'like', 'SP%');

                    // Mapping status
                    $statusMap = [
                        'pending'   => 'submitted',
                        'confirmed' => 'confirmed',
                        'canceled'  => 'cancelled',
                        'completed' => 'completed',
                    ];

                    // Filter berdasarkan status
                    if ($status !== 'all' && isset($statusMap[$status])) {
                        $query->where('status', $statusMap[$status]);
                    }

                    // Filter berdasarkan pencarian
                    if ($search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('kode_pesanan', 'like', "%{$search}%")
                            ->orWhere('nama_gabungan', 'like', "%{$search}%")
                            ->orWhere('nama_cpp', 'like', "%{$search}%")
                            ->orWhere('nama_cpw', 'like', "%{$search}%")
                            ->orWhere('phone_gabungan', 'like', "%{$search}%")
                            ->orWhere('phone_cpp', 'like', "%{$search}%")
                            ->orWhere('phone_cpw', 'like', "%{$search}%");
                        });
                    }

                    // Ambil data booking sesuai query yang sudah difilter
                    $bookings = $query->orderBy('start_time')->get();

                    // Ambil data lainnya seperti packages, addons, temas
                    $packages = Package::orderBy('order')->get();
                    $addons   = Addon::where('is_active', true)->orderBy('kategori')->orderBy('nama')->get();
                    $temas    = TemaBaju::orderBy('order')->get();

                    $addonGroups = $addons->groupBy('kategori');

                    // Menggunakan view untuk memuat halaman utama dengan data yang sudah disiapkan
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page'         => $page,
                        'bookings'     => $bookings,
                        'selectedDate' => $selectedDate,
                        'status'       => $status,
                        'search'       => $search,
                        'packages'     => $packages,
                        'addons'       => $addons,
                        'temas'        => $temas,
                        'addonGroups' => $addonGroups,
                    ]);
                }
                if ($page === 'GalleryAttire') {
                    // Isi dengan data yang diperlukan untuk Gallery Attire
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                    ]);
                }
                if ($page === 'JadwalKerja') {

                    $weekOffset = (int) $request->input('week', 0);

                    $startOfWeek = now()
                        ->startOfWeek()
                        ->addWeeks($weekOffset);

                    $endOfWeek = $startOfWeek->copy()->addDays(6);

                    $bookings = BookingClient::with([
                        'skemaKerja.editor',
                        'skemaKerja.fotografer',
                        'skemaKerja.videografer',
                        'skemaKerja.makeup',
                        'skemaKerja.attire',
                    ])
                    ->whereBetween('photoshoot_date', [
                        $startOfWeek->toDateString(),
                        $endOfWeek->toDateString(),
                    ])
                    ->orderBy('photoshoot_date')
                    ->orderBy('start_time')
                    ->get();

                    foreach ($bookings as $booking) {
                        $booking->skemaKerja()->firstOrCreate([
                        'booking_client_id' => $booking->id,
                    ]);
                    }

                    $bookingsByDate = $bookings->groupBy(fn ($b) =>
                        $b->photoshoot_date->format('Y-m-d')
                    );

                    $karyawanByRole = [
                        'editor' => DataDiriKaryawan::where('role', 'EDITOR')->get(),
                        'photografer' => DataDiriKaryawan::where('role', 'PHOTOGRAFER')->get(),
                        'videografer' => DataDiriKaryawan::where('role', 'VIDEOGRAFER')->get(),
                        'makeup' => DataDiriKaryawan::where('role', 'MAKE_UP')->get(),
                        'attire' => DataDiriKaryawan::where('role', 'ATTIRE')->get(),
                    ];

                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page'           => $page,
                        'startOfWeek'    => $startOfWeek,
                        'bookingsByDate' => $bookingsByDate,
                        'karyawanByRole' => $karyawanByRole,
                    ]);
                }
                if ($page === 'Statistik') {
                    // Isi dengan data yang diperlukan untuk Statistik
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                    ]);
                }
                if ($page === 'MenuPanel.Berkas') {
                    // Isi dengan data yang diperlukan untuk Berkas
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                    ]);
                }
                if ($page === 'StatistikContent.StatistikSurvey') {

                    $services = [
                        'Fotografer',
                        'Videografer',
                        'MUA',
                        'Admin Studio',
                        'Attire ( Busana )',
                        'Tim Fitting',
                        'Admin Attire'
                    ];

                    $futureServices = [
                        'Post Wedding',
                        'Maternity',
                        'Family Portrait',
                        'Anniversary Session'
                    ];

                    $search = $request->input('search');
                    $scoreFilter = $request->input('score');
                    $serviceFilter = $request->input('service');
                    $futureFilter = $request->input('future_service');
                    $createdMonth = $request->input('created_month');
                    $photoMonth = $request->input('photo_month');
                    $perPage = 20;
                    $query = Survey::query();
                    if (!empty($search)) {

                        $query->where(
                            'customer_name',
                            'like',
                            "%{$search}%"
                        );

                    }
                    if (!empty($scoreFilter)) {

                        $query->where(
                            'recommendation_score',
                            $scoreFilter
                        );

                    }

                    if (!empty($serviceFilter)) {

                        $query->whereJsonContains(
                            'favorite_services',
                            $serviceFilter
                        );

                    }

                    if (!empty($futureFilter)) {

                        $query->whereJsonContains(
                            'future_services',
                            $futureFilter
                        );

                    }

                    if (!empty($createdMonth)) {

                        $query->whereMonth(
                            'created_at',
                            $createdMonth
                        );

                    }
                    if (!empty($photoMonth)) {

                        $query->whereMonth(
                            'photo_date',
                            $photoMonth
                        );

                    }

                    $query->latest();
                    $allDataRaw = $query->get();
                    $allData = $allDataRaw->unique(function ($item) {
                        return strtolower(trim($item->customer_name))
                            . '_'
                            . $item->photo_date
                            . '_'
                            . $item->recommendation_score;

                    });

                    $duplicateCount = $allDataRaw->count() - $allData->count();
                    $dataPageBefore = $query
                    ->paginate($perPage)
                    ->withQueryString();
                    $afterCollection = $allData
                        ->sortByDesc('created_at')
                        ->values();

                    $currentPageAfter = request()->get(
                        'after_page',
                        1
                    );

                    $currentItemsAfter = $afterCollection->slice(
                        ($currentPageAfter - 1) * $perPage,
                        $perPage
                    )->values();

                    $dataPageAfter = new \Illuminate\Pagination\LengthAwarePaginator(
                        $currentItemsAfter,
                        $afterCollection->count(),
                        $perPage,
                        $currentPageAfter,
                        [
                            'path' => request()->url(),
                            'query' => request()->query(),
                            'pageName' => 'after_page',
                        ]
                    );

                    $favoriteBefore = [];
                    foreach ($services as $service) {
                        $favoriteBefore[$service] = 0;
                    }
                    foreach ($allDataRaw as $survey) {

                        if (is_array($survey->favorite_services)) {

                            foreach ($survey->favorite_services as $service) {

                                if (isset($favoriteBefore[$service])) {
                                    $favoriteBefore[$service]++;
                                }

                            }

                        }

                    }

                    $favoriteAfter = [];
                    foreach ($services as $service) {
                        $favoriteAfter[$service] = 0;
                    }

                    foreach ($allData as $survey) {
                        if (is_array($survey->favorite_services)) {

                            foreach ($survey->favorite_services as $service) {

                                if (isset($favoriteAfter[$service])) {
                                    $favoriteAfter[$service]++;
                                }

                            }

                        }

                    }

                    $futureBefore = [];
                    foreach ($futureServices as $service) {
                        $futureBefore[$service] = 0;
                    }

                    foreach ($allDataRaw as $survey) {
                        if (is_array($survey->future_services)) {
                            foreach ($survey->future_services as $service) {
                                if (isset($futureBefore[$service])) {
                                    $futureBefore[$service]++;
                                }
                            }
                        }
                    }

                    $futureAfter = [];
                    foreach ($futureServices as $service) {
                        $futureAfter[$service] = 0;
                    }

                    foreach ($allData as $survey) {
                        if (is_array($survey->future_services)) {
                            foreach ($survey->future_services as $service) {
                                if (isset($futureAfter[$service])) {
                                    $futureAfter[$service]++;
                                }
                            }
                        }
                    }

                    $scoreDistributionBefore = [];
                    $scoreDistributionAfter = [];

                    for ($i = 1; $i <= 10; $i++) {

                        $scoreDistributionBefore[$i] = $allDataRaw
                            ->where('recommendation_score', $i)
                            ->count();

                        $scoreDistributionAfter[$i] = $allData
                            ->where('recommendation_score', $i)
                            ->count();

                    }

                    $statsBefore = [
                        'total' => $allDataRaw->count(),
                        'duplikat' => $duplicateCount,
                        'nama_kosong' => $allDataRaw
                            ->whereNull('customer_name')
                            ->count(),
                        'tanggal_kosong' => $allDataRaw
                            ->whereNull('photo_date')
                            ->count(),
                        'feedback_kosong' => $allDataRaw
                            ->whereNull('feedback')
                            ->count(),
                        'favorite_kosong' => $allDataRaw
                            ->filter(fn($item) => empty($item->favorite_services))
                            ->count(),
                        'rata_score' => round(
                            $allDataRaw->avg('recommendation_score'),
                            2
                        ),

                    ];

                    $statsAfter = [

                        'total' => $allData->count(),
                        'duplikat' => 0,
                        'nama_kosong' => 0,
                        'tanggal_kosong' => 0,
                        'feedback_kosong' => 0,
                        'favorite_kosong' => 0,
                        'rata_score' => round(
                            $allData->avg('recommendation_score'),
                            2
                        ),

                    ];

                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [

                        'page' => $page,
                        'services' => $services,
                        'favoriteBefore' => $favoriteBefore,
                        'favoriteAfter' => $favoriteAfter,
                        'futureServices' => $futureServices,
                        'futureBefore' => $futureBefore,
                        'futureAfter' => $futureAfter,
                        'scoreDistributionBefore' => $scoreDistributionBefore,
                        'scoreDistributionAfter' => $scoreDistributionAfter,
                        'statsBefore' => $statsBefore,
                        'statsAfter' => $statsAfter,
                        'surveys' => $allDataRaw,
                        'dataPageBefore' => $dataPageBefore,
                        'dataPageAfter' => $dataPageAfter,
                        'search' => $search,
                        'scoreFilter' => $scoreFilter,
                        'serviceFilter' => $serviceFilter,
                        'createdMonth' => $createdMonth,
                        'photoMonth' => $photoMonth,

                    ]);
                }
                if ($page === 'StatistikContent.StatistikReview') {

                    $sort = $request->sort;

                    $googleReviews = GoogleReview::query();

                    if ($sort == '5star') {

                        $googleReviews
                            ->where('rating', 5)
                            ->orderBy('review_date', 'desc');

                    } elseif ($sort == '4star') {

                        $googleReviews
                            ->where('rating', 4)
                            ->orderBy('review_date', 'desc');

                    } elseif ($sort == 'oldest') {

                        $googleReviews
                            ->orderBy('review_date', 'asc');

                    } else {

                        $googleReviews
                            ->orderBy('review_date', 'desc');
                    }

                    $googleReviews = $googleReviews
                        ->take(300)
                        ->get();

                    return view(
                        'OPERATIONALPAGES.PAGE.EXECUTIVE',
                        [
                            'page' => $page,
                            'googleReviews' => $googleReviews,
                            'sort' => $sort,
                        ]
                    );
                }
                if ($page === 'Brand.KategoriPartnership') {
                    $brandCategories = BrandCategory::latest()->get();
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                        'brandCategories' => $brandCategories,
                    ]);
                }
                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', ['page' => $page]);
            }

            // Jika halaman tidak ditemukan
            abort(404, "Halaman $page tidak ditemukan");
        }
}
