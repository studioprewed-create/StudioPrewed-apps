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

class EXECUTIVEController extends Controller
{
    public function dashboard(Request $request){ return $this->loadPage($request, 'Dashboard'); }
    public function jadwalkerja(Request $request){ return $this->loadPage($request, 'JadwalKerja'); }
    public function jadwalpesanan(Request $request){ return $this->loadPage($request, 'JadwalPesanan'); }
    public function catalogue(Request $request){ return $this->loadPage($request, 'Catalogue'); }
    public function galleryattire(Request $request){ return $this->loadPage($request, 'GalleryAttire'); }
    public function statistik(Request $request){ return $this->loadPage($request, 'Statistik'); }
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
                'style'           => 'required|string|in:Hair,HairDo',

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
    public function storeReview(Request $request)
        {
            if (!Auth::check()) {
                return redirect()->route('login')->withErrors('Anda harus login untuk menambahkan review.');
            }

            // Validasi input yang disediakan form publik
            $validated = $request->validate([
                'rating'  => 'required|integer|min:1|max:5',
                'avatar'  => 'nullable|image|max:5120',
                'content' => 'nullable|string',
            ]);

            // Overwrite/isi otomatis dari user login (abaikan hidden input dari form kalau ada)
            $user = Auth::user();
            $validated['name'] = $user->name ?? 'User';
            $validated['role'] = $user->role ?? 'CLIENT';
            $validated['date'] = now()->toDateString();

            if ($request->hasFile('avatar')) {
                $validated['avatar'] = $request->file('avatar')->store('homepage', 'public');
            }
            $review = new Review($validated);
            $review->active = true;
            $review->save();

            return redirect()->route('homepage')->with('success', 'Berhasil ditambahkan!');
        }
    public function index()
        {
            $slides  = HeroSlide::where('active',1)->orderBy('order')->get();
            $marquees = Marquee::where('active',1)->orderBy('order')->get();
            $aboutus = AboutUs::active()->orderBy('order')->get();
            $model1 = AboutUs::active()->where('model_type', 'model1')->orderBy('order')->get();
            $model2 = AboutUs::active()->where('model_type', 'model2')->orderBy('order')->get();
            $model3 = AboutUs::active()->where('model_type', 'model3')->orderBy('order')->get();
            $reviews = Review::where('active',1)->latest()->get();
            $heroes  = HeroContent::where('active',1)->orderBy('order')->get();
            $faqs    = Faq::where('active',1)->orderBy('order')->get();
            $portraitServices = Service::active()->orderBy('order')->get();

            return view('HOMEPAGES.PAGE.Dashboard', compact('slides', 'marquees','aboutus','model1', 'model2', 'model3', 'reviews','heroes','faqs','portraitServices'));
        }
    public function Portofolio(Request $request)
        {
            $slides  = HeroSlide::where('active',1)->orderBy('order')->get();
            $category = $request->query('category');
            $allowedCategories = ['prewed','family','maternity','postwedding','beauty','birthday'];
            if (!in_array($category, $allowedCategories, true)) {
                $category = null;
            }
            $galleries = GalleryItem::where('active',1)->orderBy('order')->get();

            return view('HOMEPAGES.PAGE.Portofolio', [
                'slides'           => $slides,
                'galleries'        => $galleries,
                'selectedCategory' => $category,
            ]);
        }
    public function Account(Request $request)
        {
            $user = $request->user(); // sama dengan Auth::user()

            $slides   = HeroSlide::where('active',1)->orderBy('order')->get();
            $dataDiri = $user->dataDiri; // lewat relasi

            $bookings = BookingClient::with(['package']) // opsional kalau ada relasi package()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

            return view('HOMEPAGES.PAGE.Account', [
                'slides'   => $slides,
                'user'     => $user,
                'dataDiri' => $dataDiri,
                'bookings' => $bookings,
            ]);
        }

        public function storeAccount(Request $request)
        {
            $user = $request->user();

            // Cegah double data diri
            if ($user->dataDiri) {
                return redirect()
                    ->route('Account')
                    ->with('warning', 'Data diri sudah ada, silakan update.');
            }

            $validated = $request->validate([
                'nama'                   => 'required|string|max:255',
                'phone'                  => 'nullable|string|max:20',
                'jenis_kelamin'          => 'nullable|in:laki-laki,perempuan',
                'tanggal_lahir'          => 'nullable|date',
                'tanggal_pernikahan'     => 'nullable|date',
                'nama_pasangan'          => 'nullable|string|max:255',
                'phone_pasangan'         => 'nullable|string|max:20',
                'jenis_kelamin_pasangan' => 'nullable|in:laki-laki,perempuan',
                'tanggal_lahir_pasangan' => 'nullable|date',
            ]);

            $validated['user_id'] = $user->id;

            DataDiri::create($validated);

            return redirect()
                ->route('Account')
                ->with('success', 'Data diri berhasil disimpan.');
        }

        public function updateAccount(Request $request, $id)
        {
            $user     = $request->user();
            $dataDiri = DataDiri::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $validated = $request->validate([
                'nama'                   => 'required|string|max:255',
                'phone'                  => 'nullable|string|max:20',
                'jenis_kelamin'          => 'nullable|in:laki-laki,perempuan',
                'tanggal_lahir'          => 'nullable|date',
                'tanggal_pernikahan'     => 'nullable|date',
                'nama_pasangan'          => 'nullable|string|max:255',
                'phone_pasangan'         => 'nullable|string|max:20',
                'jenis_kelamin_pasangan' => 'nullable|in:laki-laki,perempuan',
                'tanggal_lahir_pasangan' => 'nullable|date',
            ]);

            $dataDiri->update($validated);

            return redirect()
                ->route('Account')
                ->with('success', 'Data diri berhasil diperbarui.');
        }

        public function destroyAccount($id)
        {
            $dataDiri = DataDiri::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $dataDiri->delete();

            return redirect()
                ->route('Account')
                ->with('success', 'Data diri berhasil dihapus.');
        }
    public function Pricelist()
        {
            $slides  = HeroSlide::where('active',1)->orderBy('order')->get();
            $promos  = PromoBanner::where('active',1)->orderBy('order')->get();
            $packages= Package::all();
            $temas   = TemaBaju::where('active',1)->orderBy('order')->get();
            $addons   = Addon::active()->orderBy('kategori')->get();

            $dataDiri = null;

            if (Auth::check()) {
                $dataDiri = DataDiri::where('user_id', Auth::id())->first();
            }
            return view('HOMEPAGES.PAGE.Pricelist', compact('slides','promos','packages','temas','addons','dataDiri'));
        }
    public function create(Request $request, $section)
        {
            return view('OPERATIONALPAGES.PAGE.EXECUTIVE', compact('section'));
        }
    public function store(Request $request, $section)
        {   $redirectMap = [
                'slide'     => 'MenuPanel.HomePages.Dashboard',
                'marquee'   => 'MenuPanel.HomePages.Dashboard',
                'aboutus'   => 'MenuPanel.HomePages.Dashboard',
                'service'   => 'MenuPanel.HomePages.Dashboard',
                'review'    => 'MenuPanel.HomePages.Dashboard',
                'hero'      => 'MenuPanel.HomePages.Dashboard',
                'faq'       => 'MenuPanel.HomePages.Dashboard',
                'gallery'   => 'MenuPanel.HomePages.Portofolio',
                'promo'     => 'MenuPanel.HomePages.Pricelist',
                'addon'     => 'MenuPanel.HomePages.Pricelist',
                'user'      => 'DataAkun',
                'package'   => 'Catalogue',
                'temabaju'  => 'Catalogue',
                'bookingclient' => 'JadwalPesanan',    
            ];

            $redirectPage = $redirectMap[$section] ?? 'MenuPanel.HomePages.Dashboard';
            
            if ($section === 'slide') {
                $validated = $request->validate([
                    'title' => 'nullable|string|max:255',
                    'subtitle' => 'nullable|string|max:255',
                    'image' => 'required|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:307200',
                    'order' => 'nullable|integer',
                ]);

                $validated['image'] = $request->file('image')->store('homepage', 'public');
                $validated['order'] = $this->cleanOrder($request->input('order'), HeroSlide::class);
                $validated['active'] = $request->boolean('active');

                HeroSlide::create($validated);
            }
            elseif ($section === 'marquee') {
                $validated = $request->validate([
                    'text'       => 'required|string|max:255',
                    'icon_class' => 'nullable|string|max:255',
                    'order'      => 'nullable|integer',
                ]);

                $validated['order']  = $this->cleanOrder($request->input('order'), Marquee::class);
                $validated['active'] = $request->boolean('active');

                Marquee::create($validated);
            }
            elseif ($section === 'aboutus') {
                $validated = $request->validate([
                    'title'       => 'required|string|max:255',
                    'subtitle'    => 'nullable|string|max:255',
                    'description' => 'nullable|string',
                    'model_type'  => 'required|in:model1,model2,model3',
                    'order'       => 'nullable|integer',
                    'active'      => 'sometimes|boolean',
                    'images.*'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
                ]);

                $imageFiles = [];
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $imageFiles[] = $file->store('aboutus', 'public');
                    }
                }

                $validated['image'] = $imageFiles;
                $validated['active'] = $request->boolean('active', true);

                AboutUs::create($validated);
            }
            elseif ($section === 'service') {
                $validated = $request->validate([
                    'title'       => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
                    'order'       => 'nullable|integer',
                    'category'    => 'required|string|in:prewed,family,maternity,postwedding,beauty,birthday',
                ]);

                $path = $request->file('image')->store('services', 'public');

                Service::create([
                    'title'       => $validated['title'],
                    'description' => $validated['description'] ?? null,
                    'image'       => $path,
                    'category'    => $validated['category'],
                    'order'       => $this->cleanOrder($request->input('order'), Service::class),
                    'active'      => $request->boolean('active', true),
                ]);
            }
            elseif ($section === 'review') {
                if (!Auth::check()) {
                    return back()->withErrors('Anda harus login untuk menambahkan review.')->withInput();
                }

                $validated = $request->validate([
                    'avatar'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                    'rating'  => 'nullable|integer|min:1|max:5',
                    'content' => 'nullable|string',
                ]);

                $user = Auth::user();
                $validated['name'] = $user->name ?? 'User';
                $validated['role'] = $user->role ?? 'CLIENT';
                $validated['date'] = now()->toDateString();

                if ($request->hasFile('avatar')) {
                    $validated['avatar'] = $request->file('avatar')->store('homepage','public');
                }

                $review = new Review($validated);
                $review->active = $request->boolean('active');
                $review->save();
            }
            elseif ($section === 'hero') {
                $validated = $request->validate([
                    'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
                    'order' => 'nullable|integer',
                ]);

                $path = $request->file('image')->store('homepage','public');

                HeroContent::create([
                    'image'  => $path,
                    'order'  => $this->cleanOrder($request->input('order'), HeroContent::class),
                    'active' => $request->boolean('active'),
                ]);
            }
            elseif ($section === 'faq') {
                $validated = $request->validate([
                    'question' => 'required|string',
                    'answer'   => 'nullable|string',
                    'order'    => 'nullable',
                ]);

                $validated['order']  = $this->cleanOrder($request->input('order'), Faq::class);
                $validated['active'] = $request->boolean('active');

                Faq::create($validated);
            }
            elseif ($section === 'gallery') {
                $validated = $request->validate([
                    'title'       => 'nullable|string|max:255',
                    'description' => 'nullable|string',
                    'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
                    'order'       => 'nullable|integer',
                    'category'    => 'nullable|string|in:prewed,family,maternity,postwedding,beauty,birthday',
                ]);

                $validated['image'] = $request->file('image')->store('homepage','public');
                $validated['order'] = $this->cleanOrder($request->input('order'), GalleryItem::class);
                $validated['active']= $request->boolean('active');

                GalleryItem::create($validated);
            }
            elseif ($section === 'promo') {
                $validated = $request->validate([
                    'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
                    'order' => 'nullable|integer',
                ]);

                $path = $request->file('image')->store('homepage','public');

                PromoBanner::create([
                    'image'  => $path,
                    'order'  => $this->cleanOrder($request->input('order'), PromoBanner::class),
                    'active' => $request->boolean('active'),
                ]);
            }
            elseif ($section === 'user') {
                $validated = $request->validate([
                    'name'     => 'required|string|max:255',
                    'email'    => 'required|string|email|unique:users',
                    'password' => 'required|string|min:6|confirmed',
                    'role'     => 'required|string',
                ]);

                User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => $request->role,
                ]);
            }
            elseif ($section === 'temabaju') {
                $validated = $request->validate([
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
                    foreach ($request->file('images') as $img) {
                        $imagePaths[] = $img->store('tema_baju', 'public');
                    }
                }

                TemaBaju::create([
                    'nama'     => $validated['nama'],
                    'images'   => json_encode($imagePaths),
                    'detail'   => $validated['detail'],
                    'designer' => $validated['designer'],
                    'harga'    => $validated['harga'],
                    'kode'     => $validated['kode'],
                    'ukuran'   => $validated['ukuran'],
                    'tipe'     => $validated['tipe'],
                ]);
            }
            elseif ($section === 'package') {
                $validated = $request->validate([
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
                    $imagePath = $request->file('images')->store('packages', 'public');
                }

                $maxOrder = Package::max('order') ?? 0;

                Package::create([
                    'nama_paket' => $validated['nama_paket'],
                    'deskripsi'  => $validated['deskripsi'] ?? null,
                    'harga'      => $validated['harga'],
                    'durasi'     => $validated['durasi'],
                    'discount'   => $validated['discount'] ?? 0,
                    'notes'      => $validated['notes'],
                    'konsep'     => $validated['konsep'],
                    'rules'      => $validated['rules'],
                    'images'     => $imagePath,
                    'order'      => $maxOrder + 1,
                ]);
            }
            elseif ($section === 'addon') {
                $validated = $request->validate([
                    'nama'      => 'required|string|max:255',
                    'kode'      => 'nullable|string|max:50|unique:addons,kode',
                    'kategori'  => 'required|in:1,2,3', // 1=slot, 2=tema, 3=fitur lain
                    'deskripsi' => 'nullable|string',
                    'harga'     => 'required|integer|min:0',
                    // durasi tambahan utk kategori extra slot (menit: 60 / 120 dsb)
                    'durasi'    => 'nullable|integer|min:0',
                    // kapasitas slot tambahan (opsional)
                    'kapasitas' => 'nullable|integer|min:1',
                    'is_active' => 'sometimes|boolean',
                ]);

                $kategori = (int) $validated['kategori'];

                Addon::create([
                    'nama'      => $validated['nama'],
                    'kode'      => $validated['kode'] ?? null,
                    'kategori'  => $kategori,
                    'deskripsi' => $validated['deskripsi'] ?? null,
                    'harga'     => (int) $validated['harga'],
                    'durasi'    => $validated['durasi'] ?? null,
                    'kapasitas' => $validated['kapasitas'] ?? null,
                    'is_active' => $request->boolean('is_active', true),
                ]);
            }
            elseif ($section === 'bookingclient') {
                // Booking dari ADMIN untuk JadwalPesanan

                $v = $request->validate([
                    'nama_cpp'        => 'required|string|max:100',
                    'phone_cpp'       => 'required|string|max:30',
                    'nama_cpw'        => 'required|string|max:100',
                    'phone_cpw'       => 'required|string|max:30',
                    'photoshoot_date' => 'required|date',
                    'start_time'      => 'required|date_format:H:i',
                    'end_time'        => 'required|date_format:H:i|after:start_time',
                    'package_id'      => 'nullable|exists:packages,id',
                    'style'           => 'required|string|in:Hair,HairDo',
                    'status'          => 'required|string|in:submitted,confirmed,cancelled,completed',
                    'notes'           => 'nullable|string',
                ]);

                // Hitung harga dari package (kalau ada)
                $package      = $v['package_id'] ? Package::find($v['package_id']) : null;
                $packagePrice = $package ? (int) $package->harga : 0;
                $addonsTotal  = 0; // di admin modal simple, belum ada addons
                $grandTotal   = $packagePrice + $addonsTotal;

                // Generate kode pesanan
                $kode = 'SPADM' . now()->format('YmdHis');

                $booking = BookingClient::create([
                    'user_id'         => optional(Auth::user())->id,

                    'nama_cpp'        => $v['nama_cpp'],
                    'email_cpp'       => null,
                    'phone_cpp'       => $v['phone_cpp'],
                    'alamat_cpp'      => null,

                    'nama_cpw'        => $v['nama_cpw'],
                    'email_cpw'       => null,
                    'phone_cpw'       => $v['phone_cpw'],
                    'alamat_cpw'      => null,

                    'ig_cpp'          => null,
                    'ig_cpw'          => null,
                    'tiktok_cpp'      => null,
                    'tiktok_cpw'      => null,
                    'sosmed_lain'     => null,

                    'package_id'      => $v['package_id'],
                    'package_price'   => $packagePrice,
                    'addons_total'    => $addonsTotal,
                    'grand_total'     => $grandTotal,

                    'photoshoot_date' => $v['photoshoot_date'],
                    'slot_code'       => null,
                    'photoshoot_slot' => null,
                    'start_time'      => $v['start_time'],
                    'end_time'        => $v['end_time'],

                    'extra_slot_code'       => null,
                    'extra_photoshoot_slot' => null,
                    'extra_start_time'      => null,
                    'extra_end_time'        => null,
                    'extra_minutes'         => 0,

                    'tema_id'         => null,
                    'tema_nama'       => null,
                    'tema_kode'       => null,

                    'tema2_id'        => null,
                    'tema2_nama'      => null,
                    'tema2_kode'      => null,

                    'style'           => $v['style'],
                    'wedding_date'    => null,
                    'notes'           => $v['notes'] ?? null,

                    'nama_gabungan'   => $v['nama_cpp'].' & '.$v['nama_cpw'],
                    'email_gabungan'  => null,
                    'phone_gabungan'  => $v['phone_cpp'].' & '.$v['phone_cpw'],

                    'kode_pesanan'    => $kode,
                    'status'          => $v['status'],
                ]);
            }
            return redirect()->route('executive.page', ['page' => $redirectPage])
                ->with('success', ucfirst($section).' berhasil ditambahkan!');
        }
    public function edit(Request $request, $section, $id)
        {
            $item = match($section) {
                'slide' => HeroSlide::findOrFail($id),
                'marquee' => Marquee::findOrFail($id),
                'model1'   => AboutUs::where('model_type','model1')->findOrFail($id),
                'model2'   => AboutUs::where('model_type','model2')->findOrFail($id),
                'model3'   => AboutUs::where('model_type','model3')->findOrFail($id),
                'review'  => Review::findOrFail($id),
                'hero'    => HeroContent::findOrFail($id),
                'faq'     => Faq::findOrFail($id),
                'gallery' => GalleryItem::findOrFail($id),
                'promo'   => PromoBanner::findOrFail($id),
                'user' => User::findOrFail($id),
                'temabaju' => TemaBaju::findOrFail($id),
                'package'  => Package::findOrFail($id),
                'addon'   => Addon::findOrFail($id),
                default => null,
            };

            return view('OPERATIONALPAGES.PAGE.EXECUTIVE.edit', compact('section', 'item'));
        }
    public function update(Request $request, $section, $id)
        {
            $redirectMap = [
                'slide'     => 'MenuPanel.HomePages.Dashboard',
                'marquee'   => 'MenuPanel.HomePages.Dashboard',
                'aboutus'   => 'MenuPanel.HomePages.Dashboard',
                'service'   => 'MenuPanel.HomePages.Dashboard',
                'review'    => 'MenuPanel.HomePages.Dashboard',
                'hero'      => 'MenuPanel.HomePages.Dashboard',
                'faq'       => 'MenuPanel.HomePages.Dashboard',
                'gallery'   => 'MenuPanel.HomePages.Portofolio',
                'promo'     => 'MenuPanel.HomePages.Pricelist',
                'addon'     => 'MenuPanel.HomePages.Pricelist',
                'user'      => 'DataAkun',
                'package'   => 'Catalogue',
                'temabaju'  => 'Catalogue',
                'bookingclient' => 'JadwalPesanan',
            ];

            $redirectPage = $redirectMap[$section] ?? 'MenuPanel.HomePages.Dashboard';

            if ($section === 'slide') {
                $slide = HeroSlide::findOrFail($id);
                $validated = $request->validate([
                    'title' => 'nullable|string|max:255',
                    'subtitle' => 'nullable|string|max:255',
                    'image' => 'nullable|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:307200',
                    'order' => 'nullable|integer',
                ]);

                if ($request->hasFile('image')) {
                    if ($slide->image && Storage::disk('public')->exists($slide->image)) {
                        Storage::disk('public')->delete($slide->image);
                    }
                    $validated['image'] = $request->file('image')->store('homepage', 'public');
                }

                $validated['order'] = $this->cleanOrder($request->input('order', $slide->order), HeroSlide::class, $slide->order);
                $validated['active'] = $request->boolean('active');

                $slide->update($validated);
            }
            elseif ($section === 'marquee') {
                $m = Marquee::findOrFail($id);
                $validated = $request->validate([
                    'text'       => 'sometimes|string|max:255',
                    'icon_class' => 'sometimes|nullable|string|max:255',
                    'order'      => 'sometimes|nullable|integer',
                ]);

                if ($request->has('order')) {
                    $validated['order'] = $this->cleanOrder($request->input('order', $m->order), Marquee::class, $m->order);
                }
                if ($request->exists('active')) {
                    $validated['active'] = $request->boolean('active');
                }

                $m->update($validated);
            }
            elseif ($section === 'aboutus') {
                $item = AboutUs::findOrFail($id);

                $validated = $request->validate([
                    'title'       => 'sometimes|required|string|max:255',
                    'subtitle'    => 'sometimes|nullable|string|max:255',
                    'description' => 'sometimes|nullable|string',
                    'model_type'  => 'sometimes|required|in:model1,model2,model3',
                    'order'       => 'sometimes|nullable|integer',
                    'active'      => 'sometimes|boolean',
                    'images.*'    => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:5120',
                ]);

                // Jika upload baru, replace field 'image' dengan array baru
                if ($request->hasFile('images')) {
                    // hapus file lama
                    if (is_array($item->image)) {
                        foreach ($item->image as $old) {
                            if (Storage::disk('public')->exists($old)) {
                                Storage::disk('public')->delete($old);
                            }
                        }
                    }

                    $imageFiles = [];
                    foreach ($request->file('images') as $file) {
                        $imageFiles[] = $file->store('aboutus','public');
                    }
                    $validated['image'] = $imageFiles;
                }

                $validated['active'] = $request->boolean('active', $item->active);

                $item->update($validated);
            }
            elseif ($section === 'service') {
                $service = Service::findOrFail($id);

                $validated = $request->validate([
                    'title'       => 'sometimes|string|max:255',
                    'description' => 'sometimes|nullable|string',
                    'image'       => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                    'order'       => 'sometimes|nullable|integer',
                    'category'    => 'sometimes|nullable|string|in:prewed,family,maternity,postwedding,beauty,birthday',
                ]);

                // ganti image jika upload baru
                if ($request->hasFile('image')) {
                    if ($service->image && Storage::disk('public')->exists($service->image)) {
                        Storage::disk('public')->delete($service->image);
                    }
                    $validated['image'] = $request->file('image')->store('services', 'public');
                }

                if ($request->has('order')) {
                    $validated['order'] = $this->cleanOrder(
                        $request->input('order', $service->order),
                        Service::class,
                        $service->order
                    );
                }

                if ($request->exists('active')) {
                    $validated['active'] = $request->boolean('active');
                }

                $service->update($validated);
            }
            elseif ($section === 'review') {
                $r = Review::findOrFail($id);
                $validated = $request->validate([
                    'name'    => 'sometimes|string|max:255',
                    'role'    => 'sometimes|nullable|string',
                    'avatar'  => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                    'rating'  => 'sometimes|nullable|integer|min:1|max:5',
                    'content' => 'sometimes|nullable|string',
                    'date'    => 'sometimes|nullable|string',
                ]);

                if ($request->hasFile('avatar')) {
                    if ($r->avatar && Storage::disk('public')->exists($r->avatar)) {
                        Storage::disk('public')->delete($r->avatar);
                    }
                    $validated['avatar'] = $request->file('avatar')->store('homepage','public');
                }

                $r->fill($validated);
                $r->active = $request->boolean('active');
                $r->save();
            }
            elseif ($section === 'hero') {
                $hero = HeroContent::findOrFail($id);
                $validated = $request->validate([
                    'image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                    'order' => 'sometimes|nullable|integer',
                ]);

                if ($request->hasFile('image')) {
                    if ($hero->image && Storage::disk('public')->exists($hero->image)) {
                        Storage::disk('public')->delete($hero->image);
                    }
                    $hero->image = $request->file('image')->store('homepage','public');
                }

                $hero->order  = $this->cleanOrder($request->input('order', $hero->order), HeroContent::class, $hero->order);
                $hero->active = $request->boolean('active');
                $hero->save();
            }
            elseif ($section === 'faq') {
                $f = Faq::findOrFail($id);
                $validated = $request->validate([
                    'question' => 'sometimes|string',
                    'answer'   => 'sometimes|nullable|string',
                    'order'    => 'sometimes|nullable',
                ]);

                $f->question = array_key_exists('question',$validated) ? $validated['question'] : $f->question;
                $f->answer   = array_key_exists('answer',$validated)   ? $validated['answer']   : $f->answer;
                $f->order    = $this->cleanOrder($request->input('order', $f->order), Faq::class, $f->order);
                $f->active   = $request->boolean('active');
                $f->save();
            }
            elseif ($section === 'promo') {
                $p = PromoBanner::findOrFail($id);
                $validated = $request->validate([
                    'image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                    'order' => 'sometimes|nullable|integer',
                ]);

                if ($request->hasFile('image')) {
                    if ($p->image && Storage::disk('public')->exists($p->image)) {
                        Storage::disk('public')->delete($p->image);
                    }
                    $p->image = $request->file('image')->store('homepage','public');
                }

                if ($request->filled('order')) {
                    $p->order = (int)$request->input('order');
                }

                $p->active = $request->boolean('active');
                $p->save();
            }
            elseif ($section === 'user') {
                $user = User::findOrFail($id);

                $request->validate([
                    'name'  => 'required|string|max:255',
                    'email' => 'required|string|email|unique:users,email,'. $user->id,
                    'role'  => 'required|string',
                ]);

                $user->name  = $request->name;
                $user->email = $request->email;
                $user->role  = $request->role;

                if ($request->filled('password')) {
                    $request->validate([
                        'password' => 'string|min:6|confirmed',
                    ]);
                    $user->password = Hash::make($request->password);
                }

                $user->save();
            }
            elseif ($section === 'temabaju') {
                $item = TemaBaju::findOrFail($id);

                $validated = $request->validate([
                    'nama'       => 'required|string|max:255',
                    'images.*'   => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                    'detail'     => 'required|string',
                    'designer'   => 'required|string|max:255',
                    'harga'      => 'required|numeric',
                    'kode'       => 'required|string|max:50|unique:tema_baju,kode,' . $item->id,
                    'ukuran'     => 'required|string|max:100',
                    'tipe'       => 'required|string|max:50',
                ]);

                $imagePaths = $item->images_array;

                if ($request->hasFile('images')) {
                    foreach ($imagePaths as $old) {
                        Storage::disk('public')->delete($old);
                    }

                    $imagePaths = [];
                    foreach ($request->file('images') as $img) {
                        $imagePaths[] = $img->store('tema_baju', 'public');
                    }
                }

                $item->update([
                    'nama'     => $validated['nama'],
                    'images'   => json_encode($imagePaths),
                    'detail'   => $validated['detail'],
                    'designer' => $validated['designer'],
                    'harga'    => $validated['harga'],
                    'kode'     => $validated['kode'],
                    'ukuran'   => $validated['ukuran'],
                    'tipe'     => $validated['tipe'],
                ]);
            }
            elseif ($section === 'package') {
                $item = Package::findOrFail($id);

                $validated = $request->validate([
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

                $imagePath = $item->images;

                if ($request->hasFile('images')) {
                    if ($imagePath) Storage::disk('public')->delete($imagePath);
                    $imagePath = $request->file('images')->store('packages', 'public');
                }

                $item->update([
                    'nama_paket' => $validated['nama_paket'],
                    'deskripsi'  => $validated['deskripsi'],
                    'harga'      => $validated['harga'],
                    'durasi'     => $validated['durasi'],
                    'discount'   => $validated['discount'] ?? 0,
                    'notes'      => $validated['notes'],
                    'konsep'     => $validated['konsep'],
                    'rules'      => $validated['rules'],
                    'images'     => $imagePath,
                ]);
            }
            elseif ($section === 'addon') {
                $addon = Addon::findOrFail($id);

                $validated = $request->validate([
                    'nama'      => 'sometimes|string|max:255',
                    'kode'      => 'sometimes|nullable|string|max:50|unique:addons,kode,' . $addon->id,
                    'kategori'  => 'sometimes|in:1,2,3',
                    'deskripsi' => 'sometimes|nullable|string',
                    'harga'     => 'sometimes|integer|min:0',
                    'durasi'    => 'sometimes|nullable|integer|min:0',
                    'kapasitas' => 'sometimes|nullable|integer|min:1',
                    'is_active' => 'sometimes|boolean',
                ]);

                $data = [];

                foreach (['nama','kode','kategori','deskripsi','harga','durasi','kapasitas'] as $field) {
                    if (array_key_exists($field, $validated)) {
                        $data[$field] = $validated[$field];
                    }
                }

                if ($request->exists('is_active')) {
                    $data['is_active'] = $request->boolean('is_active');
                }

                $addon->update($data);
            }
            elseif ($section === 'bookingclient') {
                $booking = BookingClient::findOrFail($id);

                $v = $request->validate([
                    'nama_cpp'        => 'sometimes|required|string|max:100',
                    'phone_cpp'       => 'sometimes|required|string|max:30',
                    'nama_cpw'        => 'sometimes|required|string|max:100',
                    'phone_cpw'       => 'sometimes|required|string|max:30',
                    'photoshoot_date' => 'sometimes|required|date',
                    'start_time'      => 'sometimes|required|date_format:H:i',
                    'end_time'        => 'sometimes|required|date_format:H:i|after:start_time',
                    'package_id'      => 'sometimes|nullable|exists:packages,id',
                    'style'           => 'sometimes|required|string|in:Hair,HairDo',
                    'status'          => 'sometimes|required|string|in:submitted,confirmed,cancelled,completed',
                    'notes'           => 'sometimes|nullable|string',
                ]);

                // update basic fields
                foreach (['nama_cpp','phone_cpp','nama_cpw','phone_cpw','photoshoot_date','start_time','end_time','package_id','style','status','notes'] as $field) {
                    if (array_key_exists($field, $v)) {
                        $booking->{$field} = $v[$field];
                    }
                }

                // nama & telepon gabungan
                if (array_key_exists('nama_cpp', $v) || array_key_exists('nama_cpw', $v)) {
                    $booking->nama_gabungan = ($booking->nama_cpp ?: 'CPP').' & '.($booking->nama_cpw ?: 'CPW');
                }
                if (array_key_exists('phone_cpp', $v) || array_key_exists('phone_cpw', $v)) {
                    $booking->phone_gabungan = ($booking->phone_cpp ?: '').' & '.($booking->phone_cpw ?: '');
                }

                // update harga kalau package diubah
                if (array_key_exists('package_id', $v)) {
                    $package      = $booking->package_id ? Package::find($booking->package_id) : null;
                    $packagePrice = $package ? (int) $package->harga : 0;
                    $booking->package_price = $packagePrice;
                    // addons_total tetap, grand_total disesuaikan
                    $booking->grand_total   = $packagePrice + (int)($booking->addons_total ?? 0);
                }

                $booking->save();
            }
            return redirect()->route('executive.page', ['page' => $redirectPage])
                        ->with('success', ucfirst($section).' berhasil diperbarui!');
        }
    public function destroy(Request $request, $section, $id)
        {
            $redirectMap = [
                'slide'     => 'MenuPanel.HomePages.Dashboard',
                'marquee'   => 'MenuPanel.HomePages.Dashboard',
                'aboutus'   => 'MenuPanel.HomePages.Dashboard',
                'service'   => 'MenuPanel.HomePages.Dashboard',
                'review'    => 'MenuPanel.HomePages.Dashboard',
                'hero'      => 'MenuPanel.HomePages.Dashboard',
                'faq'       => 'MenuPanel.HomePages.Dashboard',
                'gallery'   => 'MenuPanel.HomePages.Portofolio',
                'promo'     => 'MenuPanel.HomePages.Pricelist',
                'addon'     => 'MenuPanel.HomePages.Pricelist',
                'user'      => 'DataAkun',
                'package'   => 'Catalogue',
                'temabaju'  => 'Catalogue',
                'bookingclient' => 'JadwalPesanan',

            ];

            $redirectPage = $redirectMap[$section] ?? 'MenuPanel.HomePages.Dashboard';

            $item = match($section) {
                'slide' => HeroSlide::findOrFail($id),
                'marquee' => Marquee::findOrFail($id),
                'aboutus' => AboutUs::findOrFail($id),
                'service' => Service::findOrFail($id),
                'review'  => Review::findOrFail($id),
                'hero'    => HeroContent::findOrFail($id),
                'faq'     => Faq::findOrFail($id),
                'gallery' => GalleryItem::findOrFail($id),
                'promo'   => PromoBanner::findOrFail($id),
                'user' => User::findOrFail($id),
                'temabaju' => TemaBaju::findOrFail($id),
                'package'  => Package::findOrFail($id),
                'addon'   => Addon::findOrFail($id),
                'bookingclient' => BookingClient::findOrFail($id),
                default => null,
            };

            if ($section === 'aboutus') {
                if (is_array($item->image)) {
                    foreach ($item->image as $img) {
                        if ($img && Storage::disk('public')->exists($img)) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                } else {
                    if ($item->image && Storage::disk('public')->exists($item->image)) {
                        Storage::disk('public')->delete($item->image);
                    }
                }

                $item->delete();
            }
            if (isset($item->image) && $item->image) {
                if (is_array($item->image)) {
                    foreach ($item->image as $img) {
                        if ($img && Storage::disk('public')->exists($img)) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                } else {
                    if (Storage::disk('public')->exists($item->image)) {
                        Storage::disk('public')->delete($item->image);
                    }
                }
            }

            if (isset($item->avatar) && $item->avatar && Storage::disk('public')->exists($item->avatar)) {
                Storage::disk('public')->delete($item->avatar);
            }
            elseif ($section === 'user') {
                $item->delete();
            }
            $item->delete();

            return redirect()->route('executive.page', ['page' => $redirectPage])
                        ->with('success', ucfirst($section).' berhasil diperbarui!');
        }
    public function inlineUpdate(Request $request, $section, $id = null)
        {
            // Pastikan balasan JSON
            if (!$request->expectsJson()) {
                $request->headers->set('Accept', 'application/json');
            }
            if ($section === 'about') {
                $about = AboutPage::first() ?? AboutPage::create([]);
                $data  = $request->only(['hero_title','hero_subtitle','title','description','signature']);

            if ($request->hasFile('image')) {
                    $request->validate(['image' => 'image|mimes:jpg,jpeg,png,webp|max:5120']);
                    if ($about->image && Storage::disk('public')->exists($about->image)) {
                        Storage::disk('public')->delete($about->image);
                    }
                    $data['image'] = $request->file('image')->store('homepage','public');
                    }

                    $about->update($data);

                    return response()->json([
                        'success'   => true,
                        'image_url' => $about->image ? asset('storage/'.$about->image) : null
                    ]);
            }
            if ($section === 'addon') {
                $addon = Addon::findOrFail($id);

                $validated = $request->validate([
                    'nama'      => 'sometimes|string|max:255',
                    'deskripsi' => 'sometimes|nullable|string',
                ]);

                if (array_key_exists('nama', $validated)) {
                    $addon->nama = $validated['nama'];
                }
                if (array_key_exists('deskripsi', $validated)) {
                    $addon->deskripsi = $validated['deskripsi'];
                }

                $addon->save();

                return response()->json(['success' => true]);
            }
            if ($section === 'aboutus') {
                $item = AboutUs::findOrFail($id);
                $fields = ['title', 'subtitle', 'description', 'content', 'model_type'];
                $data = $request->only($fields);
                if(isset($data['model_type'])){
                    $data['model_type'] = in_array($data['model_type'], ['model1','model2','model3'])
                        ? $data['model_type'] 
                        : $item->model_type;
                }
                if ($request->hasFile('images')) {
                    $request->validate([
                        'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
                    ]);

                    if (is_array($item->images)) {
                        foreach ($item->images as $oldImage) {
                            if (Storage::disk('public')->exists($oldImage)) {
                                Storage::disk('public')->delete($oldImage);
                            }
                        }
                    }
                    $uploadedImages = [];
                    foreach ($request->file('images') as $file) {
                        $uploadedImages[] = $file->store('aboutus', 'public');
                    }

                    $data['images'] = $uploadedImages;
                }

                elseif ($request->hasFile('image')) {
                    $request->validate(['image' => 'image|mimes:jpg,jpeg,png,webp|max:5120']);

                    if ($item->image && Storage::disk('public')->exists($item->image)) {
                        Storage::disk('public')->delete($item->image);
                    }

                    $data['image'] = $request->file('image')->store('aboutus', 'public');
                }

                if ($request->has('order')) {
                    $item->order = $this->cleanOrder($request->input('order'), AboutUs::class, $item->order);
                }

                if ($request->exists('active')) {
                    $item->active = $request->boolean('active');
                }

                $item->update($data);

                return response()->json([
                    'success'    => true,
                    'image_urls' => $item->all_image_urls, 
                    'order'      => $item->order,
                    'active'     => $item->active,
                    'model_type' => $item->model_type,
                ]);
            }

            $model = null;
            $fileField = 'image';
            switch ($section) {
                case 'slide':   $model = HeroSlide::findOrFail($id); break;
                case 'gallery': $model = GalleryItem::findOrFail($id); break;
                case 'hero':    $model = HeroContent::findOrFail($id); break;
                case 'service': $model = Service::findOrFail($id); break;
                case 'review':  $model = Review::findOrFail($id); $fileField = $request->hasFile('avatar') ? 'avatar' : 'image'; break;
                case 'faq':     $model = Faq::findOrFail($id); break;
                case 'social':  $model = Social::findOrFail($id); break;
                case 'promo':   $model = PromoBanner::findOrFail($id); break;
                case 'marquee': $model = Marquee::findOrFail($id); break;
                case 'GalleryItem': $model = GalleryItem::findOrFail($id); break;
                default:
                    return response()->json(['success'=>false,'message'=>'Unsupported inline update'], 400);
            }

            $fields = ['title','subtitle','description','icon','link','name','role','content','rating','date','question','answer','platform','handle','url','icon_class','category','text'];
            foreach ($fields as $f) {
                if ($request->has($f)) {
                    $model->{$f} = $f === 'rating' ? (int)$request->input($f) : $request->input($f);
                }
            }

            if ($request->has('order')) $model->order = $this->cleanOrder($request->input('order'), get_class($model), $model->order);
            if ($request->exists('active')) $model->active = $request->boolean('active');

            if ($request->hasFile('image') || $request->hasFile('avatar')) {
                $rule = ($section === 'slide')
                    ? [$fileField => 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:307200']
                    : [$fileField => 'image|mimes:jpg,jpeg,png,webp|max:5120'];

                $request->validate($rule);

                $column = ($section === 'review') ? 'avatar' : 'image';
                if (!$request->hasFile($column) && $request->hasFile('image') && $column === 'avatar') $column = 'avatar';
                if (!empty($model->{$column}) && Storage::disk('public')->exists($model->{$column})) {
                    Storage::disk('public')->delete($model->{$column});
                }

                $model->{$column} = $request->file($fileField)->store('homepage','public');
            }

            $model->save();

            return response()->json([
                'success'   => true,
                'image_url' => isset($model->image) && $model->image ? asset('public/storage/'.$model->image)
                            : (isset($model->avatar) && $model->avatar ? asset('public/storage/'.$model->avatar) : null),
            ]);
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
                $users = User::all();
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
                // default: hari ini
                $selectedDate = $request->input('date', now()->toDateString());
                $status       = $request->input('status', 'all');
                $search       = $request->input('search');

                $query = BookingClient::whereDate('photoshoot_date', $selectedDate);

                // mapping status UI -> DB
                $statusMap = [
                    'pending'   => 'submitted',
                    'confirmed' => 'confirmed',
                    'canceled'  => 'cancelled',
                    'completed' => 'completed', // kalau nanti kamu tambah enum ini
                ];

                if ($status !== 'all' && isset($statusMap[$status])) {
                    $query->where('status', $statusMap[$status]);
                }

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

                $bookings = $query
                    ->orderBy('start_time')
                    ->get();

                // supaya modal "Booking Baru" bisa milih paket
                $packages = Package::orderBy('order')->get();

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page'         => $page,
                    'bookings'     => $bookings,
                    'selectedDate' => $selectedDate,
                    'packages'     => $packages,
                ]);
            }
            return view('OPERATIONALPAGES.PAGE.EXECUTIVE', ['page' => $page]);
        }
    public function loadContent(Request $request, $page)
        {
            if ($page === 'DataAkun') {
                $users = User::all();
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
                $selectedDate = $request->input('date', now()->toDateString());
                $status       = $request->input('status', 'all');
                $search       = $request->input('search');

                $query = BookingClient::whereDate('photoshoot_date', $selectedDate);

                $statusMap = [
                    'pending'   => 'submitted',
                    'confirmed' => 'confirmed',
                    'canceled'  => 'cancelled',
                    'completed' => 'completed',
                ];

                if ($status !== 'all' && isset($statusMap[$status])) {
                    $query->where('status', $statusMap[$status]);
                }

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

                $bookings = $query->orderBy('start_time')->get();
                $packages = Package::orderBy('order')->get();

                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$page", compact('bookings', 'selectedDate', 'packages'));
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
                    $users = User::all();
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
                    $selectedDate = $request->input('date', now()->toDateString());
                    $status       = $request->input('status', 'all');
                    $search       = $request->input('search');

                    $query = BookingClient::whereDate('photoshoot_date', $selectedDate);

                    $statusMap = [
                        'pending'   => 'submitted',
                        'confirmed' => 'confirmed',
                        'canceled'  => 'cancelled',
                        'completed' => 'completed',
                    ];

                    if ($status !== 'all' && isset($statusMap[$status])) {
                        $query->where('status', $statusMap[$status]);
                    }

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

                    $bookings = $query->orderBy('start_time')->get();
                    $packages = Package::orderBy('order')->get();

                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page'         => $page,
                        'bookings'     => $bookings,
                        'selectedDate' => $selectedDate,
                        'packages'     => $packages,
                    ]);
                }
                if ($page === 'GalleryAttire') {
                    // Isi dengan data yang diperlukan untuk Gallery Attire
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                    ]);
                }
                if ($page === 'JadwalKerja') {
                    // Isi dengan data yang diperlukan untuk Jadwal Kerja
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                    ]);
                }

                // 8. JadwalPesanan

                // 9. Statistik
                if ($page === 'Statistik') {
                    // Isi dengan data yang diperlukan untuk Statistik
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                    ]);
                }

                // 10. Menu Panel Berkas
                if ($page === 'MenuPanel.Berkas') {
                    // Isi dengan data yang diperlukan untuk Berkas
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                    ]);
                }

                // Default jika halaman tidak ditemukan
                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', ['page' => $page]);
            }

            // Jika halaman tidak ditemukan
            abort(404, "Halaman $page tidak ditemukan");
        }
}
