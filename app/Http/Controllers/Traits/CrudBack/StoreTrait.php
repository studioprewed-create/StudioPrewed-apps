<?php

namespace App\Http\Controllers\Traits\CrudBack;

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
use Carbon\Carbon;
use App\Models\BrandCategory;
use App\Models\TACPackage;
use App\Models\KonsepAttire;
use App\Models\DESCPackage;
use App\Models\PackageLabel;

trait StoreTrait {
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
                'user'      => 'subpage/dataakun',
                'package'   => 'Catalogue/Package',
                'temabaju'  => 'Catalogue',
                'bookingexecutive' => 'JadwalPesanan',
                'googlereview' => 'subpage/statistikreview',
                'brand-category' => 'Brand.KategoriPartnership',
                'tacpackage' => 'Catalogue/LibraryCatalogue', 
                'konsepattire' => 'Catalogue/LibraryCatalogue',
                'descpackage' => 'Catalogue/LibraryCatalogue',
                'packagelabel' => 'Catalogue/LibraryCatalogue',
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
                    'deskripsi'  => 'nullable|array',
                    'deskripsi.*' => 'nullable|integer|exists:desc_packages,id',
                    'harga'      => 'required|numeric',
                    'durasi'     => 'nullable|integer',
                    'discount'   => 'nullable|numeric|min:0|max:100',
                    'notes'      => 'nullable|string',
                    'konsep'     => 'nullable|array',
                    'konsep.*'   => 'nullable|integer|exists:konsep_attires,id',
                    'label_id'   => 'nullable|array',
                    'label_id.*' => 'nullable|integer|exists:package_labels,id',
                    'tac_ids'    => 'nullable|array',
                    'tac_ids.*'  => 'nullable|integer|exists:tac_packages,id',
                    'rules'      => 'nullable|string',
                    'attire_ids' => 'nullable|array',
                    'attire_ids.*' => 'nullable|integer|exists:tema_baju,id',
                    'images'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                ]);

                $imagePath = null;
                if ($request->hasFile('images')) {
                    $imagePath = $request->file('images')->store('packages', 'public');
                }

                $maxOrder = Package::max('order') ?? 0;

                Package::create([
                    'nama_paket' => $validated['nama_paket'],
                    'deskripsi'  => $validated['deskripsi'] ?? [],
                    'harga'      => $validated['harga'],
                    'durasi'     => $validated['durasi'],
                    'discount'   => $validated['discount'] ?? 0,
                    'notes'      => $validated['notes'],
                    'konsep'     => $validated['konsep'] ?? [],
                    'label_id'   => $validated['label_id'] ?? [],
                    'rules'      => $validated['rules'],
                    'tac_ids'    => $validated['tac_ids'] ?? [],
                    'attire_ids' => $validated['attire_ids'] ?? [],
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
            elseif ($section === 'bookingexecutive') {
                /* =========================
                VALIDATION
                ========================= */
                $v = $request->validate([
                    'nama_cpp'        => 'required|string|max:100',
                    'phone_cpp'       => 'required|string|max:30',
                    'email_cpp'       => 'nullable|email|max:120',
                    'alamat_cpp'      => 'nullable|string|max:255',

                    'nama_cpw'        => 'required|string|max:100',
                    'phone_cpw'       => 'required|string|max:30',
                    'email_cpw'       => 'nullable|email|max:120',
                    'alamat_cpw'      => 'nullable|string|max:255',

                    'package_id'      => 'required|exists:packages,id',
                    'photoshoot_date' => 'required|date',
                    'slot_code'       => 'required|string|max:20',
                    'start_time'      => 'required|date_format:H:i',
                    'end_time'        => 'required|date_format:H:i',
                    'style'           => 'required|in:Hijab,HairDo',

                    'wedding_date'    => 'nullable|date',
                    'notes'           => 'nullable|string',

                    'tema_nama'       => 'nullable|string|max:100',
                    'tema_kode'       => 'nullable|string|max:50',

                    'tema2_nama'      => 'nullable|string|max:100',
                    'tema2_kode'      => 'nullable|string|max:50',

                    'addons'          => 'nullable|array',
                    'addons.*'        => 'integer|exists:addons,id',

                    'extra_slot_code' => 'nullable|string|max:20',
                    'extra_start_time'=> 'nullable|date_format:H:i',
                    'extra_end_time'  => 'nullable|date_format:H:i',
                ]);

                /* =========================
                BASIC TIME
                ========================= */
                if ($v['end_time'] <= $v['start_time']) {
                    return back()->withErrors(['end_time' => 'End time harus setelah start time']);
                }

                $start = $v['start_time'];
                $end   = $v['end_time'];

                // Wajib isi karena kolom NOT NULL
                $v['photoshoot_slot'] = $start.' - '.$end;

                $kapasitas = 2;

                /* =========================
                CEK SLOT UTAMA
                ========================= */
                $overlap = BookingClient::whereDate('photoshoot_date', $v['photoshoot_date'])
                    ->where(function ($q) use ($start,$end) {
                        $q->whereTime('start_time','<',$end)
                        ->whereTime('end_time','>',$start);
                    })
                    ->count();

                if ($overlap >= $kapasitas) {
                    return back()->withErrors(['slot_code' => 'Slot utama sudah penuh']);
                }

                /* =========================
                CEK TEMA UTAMA
                ========================= */
                if (!empty($v['tema_kode'])) {
                    $temaDipakai = BookingClient::whereDate('photoshoot_date',$v['photoshoot_date'])
                        ->where(function($q) use ($v){
                            $q->where('tema_kode',$v['tema_kode'])
                            ->orWhere('tema2_kode',$v['tema_kode']);
                        })
                        ->whereTime('start_time','<',$end)
                        ->whereTime('end_time','>',$start)
                        ->exists();

                    if ($temaDipakai) {
                        return back()->withErrors(['tema_kode'=>'Tema utama sudah dipakai di jam ini']);
                    }
                }

                /* =========================
                ADDON
                ========================= */
                $addonIds = $v['addons'] ?? [];
                $addons   = empty($addonIds)
                    ? collect()
                    : Addon::whereIn('id',$addonIds)->where('is_active',1)->get();

                $addonSlot = $addons->firstWhere('kategori',1);
                $addonTema = $addons->firstWhere('kategori',2);

                /* =========================
                EXTRA SLOT
                ========================= */
                if ($addonSlot) {
                    if (empty($v['extra_start_time']) || empty($v['extra_end_time'])) {
                        return back()->withErrors(['extra_slot_code'=>'Addon slot dipilih tapi jam kosong']);
                    }

                    $es = $v['extra_start_time'];
                    $ee = $v['extra_end_time'];

                    if ($es < $end && $ee > $start) {
                        return back()->withErrors(['extra_slot_code'=>'Extra slot overlap slot utama']);
                    }

                    $extraOverlap = BookingClient::whereDate('photoshoot_date',$v['photoshoot_date'])
                        ->whereTime('start_time','<',$ee)
                        ->whereTime('end_time','>',$es)
                        ->count();

                    if ($extraOverlap >= $kapasitas) {
                        return back()->withErrors(['extra_slot_code'=>'Extra slot penuh']);
                    }

                    $v['extra_photoshoot_slot'] = $es.' - '.$ee;
                    $v['extra_minutes'] = (int) ($addonSlot->durasi ?? 0);

                } else {
                    $v['extra_slot_code'] = null;
                    $v['extra_start_time'] = null;
                    $v['extra_end_time'] = null;
                    $v['extra_photoshoot_slot'] = null;
                    $v['extra_minutes'] = 0;
                }

                /* =========================
                TEMA TAMBAHAN
                ========================= */
                if ($addonTema && !empty($v['tema2_kode'])) {

                    if ($v['tema2_kode'] === $v['tema_kode']) {
                        return back()->withErrors(['tema2_kode'=>'Tema tambahan tidak boleh sama dengan tema utama']);
                    }

                    $dipakai = BookingClient::whereDate('photoshoot_date',$v['photoshoot_date'])
                        ->where(function($q) use ($v){
                            $q->where('tema_kode',$v['tema2_kode'])
                            ->orWhere('tema2_kode',$v['tema2_kode']);
                        })
                        ->whereTime('start_time','<',$end)
                        ->whereTime('end_time','>',$start)
                        ->exists();

                    if ($dipakai) {
                        return back()->withErrors(['tema2_kode'=>'Tema tambahan sudah dipakai di jam ini']);
                    }
                } else {
                    $v['tema2_kode'] = null;
                    $v['tema2_nama'] = null;
                }

                /* =========================
                HARGA
                ========================= */
                $package = Package::findOrFail($v['package_id']);
                $packagePrice = (int) $package->final_price;
                $addonsTotal  = (int) $addons->sum('harga');
                $grandTotal   = $packagePrice + $addonsTotal;

                /* =========================
                SAVE>fill($v);

                $booking->package_price = $packagePrice;
                $booking->addons_total  = $addonsTotal;
                $booking->grand_total   = $grandTotal;
                $booking-
                ========================= */
                $booking = new BookingClient();
                $booking->status        = 'confirmed';

                $booking->nama_gabungan  = $v['nama_cpp'].' & '.$v['nama_cpw'];
                $booking->phone_gabungan = $v['phone_cpp'].' & '.$v['phone_cpw'];

                $booking->kode_pesanan = 'SP' . now()->format('YmdHis') . Str::upper(Str::random(4));

                $booking->save();
            }
            elseif ($section === 'googlereview') {

                $json = file_get_contents(
                    storage_path('app/reviews.json')
                );

                $json = mb_convert_encoding(
                    $json,
                    'UTF-8',
                    'UTF-8'
                );

                $reviews = json_decode($json, true);

                if (!$reviews) {

                    return back()->withErrors(
                        'JSON review tidak valid'
                    );
                }

                foreach ($reviews as $review) {

                    GoogleReview::updateOrCreate(

                        [
                            'review_id' => $review['reviewId']
                        ],

                        [

                            'author_name' =>
                                $review['name']
                                ?? 'Anonymous',

                            'rating' =>
                                $review['stars']
                                ?? 0,

                            'review_text' =>
                                $review['text']
                                ?? '',

                            'profile_photo' =>
                                $review['reviewerPhotoUrl']
                                ?? null,

                            'review_images' => json_encode(
                                $review['reviewImageUrls'] ?? []
                            ),

                            'likes_count' =>
                                $review['likesCount']
                                ?? 0,

                            'review_date' => isset(
                                $review['publishedAtDate']
                            )

                                ? Carbon::parse(
                                    $review['publishedAtDate']
                                )->format('Y-m-d H:i:s')

                                : null,
                        ]
                    );
                }
            }
            elseif ($section === 'brand-category') {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string',
                ]);
                BrandCategory::create([
                    'name' => $request->name,
                    'description' => $request->description,
                ]);
            }
            elseif ($section === 'tacpackage') {
                $validated = $request->validate([
                    'content' => 'required|string',
                ]);

                TACPackage::create([
                    'content' => $validated['content'],
                    'active'  => $request->boolean('active', true),
                ]);
            }
            elseif ($section === 'konsepattire') {
                $validated = $request->validate([
                    'content' => 'required|string',
                ]);

                KonsepAttire::create([
                    'content' => $validated['content'],
                    'active'  => $request->boolean('active', true),
                ]);
            }
            elseif ($section === 'descpackage') {
                $validated = $request->validate([
                    'content' => 'required|string',
                ]);

                DESCPackage::create([
                    'content' => $validated['content'],
                    'active' => $request->boolean('active', true),
                ]);
            }
            elseif ($section === 'packagelabel') {
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                ]);

                PackageLabel::create([
                    'name' => $validated['name'],
                    'active' => $request->boolean('active', true),
                ]);
            }
            return redirect()->route('executive.page', ['page' => $redirectPage])
                ->with('success', ucfirst($section).' berhasil ditambahkan!');
        }
}