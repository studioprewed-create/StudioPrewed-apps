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

class CRUDBACKController extends Controller
{
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
                'bookingexecutive' => 'JadwalPesanan',    
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
                'bookingexecutive' => BookingClient::findOrFail($id),
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
                'bookingexecutive' => 'JadwalPesanan',
                'skemakerja' => 'JadwalKerja',
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
                    'name'     => 'required|string|max:255',
                    'email'    => 'required|email|unique:users,email,' . $user->id,
                    'role'     => 'required|string',
                    'password' => 'nullable|string|min:6|confirmed',
                ]);
                $user->name  = $request->name;
                $user->email = $request->email;
                $user->role  = $request->role;

                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();
                if ($user->role === 'CLIENT') {

                    if ($request->filled('data_diri')) {

                        $data = $request->input('data_diri');

                        if (empty($data['nama'])) {
                            $data['nama'] = $user->name;
                        }

                        $user->dataDiri()->updateOrCreate(
                            ['user_id' => $user->id],
                            $data
                        );
                    }

                    $user->dataDiriKaryawan()?->delete();
                }
                else {

                    if ($request->filled('data_diri_karyawan')) {

                        $data = $request->input('data_diri_karyawan');

                        if (empty($data['nama_lengkap'])) {
                            $data['nama_lengkap'] = $user->name;
                        }

                        $user->dataDiriKaryawan()->updateOrCreate(
                            ['user_id' => $user->id],
                            array_merge($data, [
                                'role' => $user->role,
                            ])
                        );
                    }
                    $user->dataDiri()?->delete();
                }
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
            elseif ($section === 'bookingexecutive') {

                $booking = BookingClient::findOrFail($id);

                /* ============================
                VALIDATION
                ============================ */
                $v = $request->validate([
                    'nama_cpp'        => 'sometimes|required|string|max:100',
                    'phone_cpp'       => 'sometimes|required|string|max:30',
                    'email_cpp'       => 'sometimes|nullable|email|max:120',
                    'alamat_cpp'      => 'sometimes|nullable|string|max:255',

                    'nama_cpw'        => 'sometimes|required|string|max:100',
                    'phone_cpw'       => 'sometimes|required|string|max:30',
                    'email_cpw'       => 'sometimes|nullable|email|max:120',
                    'alamat_cpw'      => 'sometimes|nullable|string|max:255',

                    'package_id'      => 'sometimes|required|exists:packages,id',
                    'photoshoot_date' => 'sometimes|required|date',
                    'photoshoot_slot' => 'nullable|string|max:20',
                    'slot_code'       => 'sometimes|required|string|max:10',
                    'start_time'      => 'sometimes|required|date_format:H:i',
                    'end_time'        => 'sometimes|required|date_format:H:i',
                    'style'           => 'sometimes|required|in:Hijab,HairDo',

                    'tema_nama'       => 'sometimes|nullable|string|max:100',
                    'tema_kode'       => 'sometimes|nullable|string|max:50',
                    'tema2_nama'      => 'sometimes|nullable|string|max:100',
                    'tema2_kode'      => 'sometimes|nullable|string|max:50',

                    'addons'          => 'sometimes|nullable|array',
                    'addons.*'        => 'integer|exists:addons,id',

                    'extra_slot_code' => 'sometimes|nullable|string|max:10',
                    'extra_start_time'=> 'sometimes|nullable|date_format:H:i',
                    'extra_end_time'  => 'sometimes|nullable|date_format:H:i',
                    'extra_minutes'  => 'sometimes|nullable|integer|min:0',

                    'notes'           => 'sometimes|nullable|string',
                    'status'          => 'sometimes|required|in:submitted,confirmed,cancelled,completed',
                ]);

                /* ============================
                EFFECTIVE VALUE
                ============================ */
                $effDate  = $v['photoshoot_date'] ?? $booking->photoshoot_date;
                $effStart = $v['start_time']      ?? $booking->start_time;
                $effEnd   = $v['end_time']        ?? $booking->end_time;

                $start = substr((string) $effStart, 0, 5);
                $end   = substr((string) $effEnd,   0, 5);

                /* Wajib generate slot string karena kolom NOT NULL */
                if ($start && $end) {
                    $v['photoshoot_slot'] = $start . ' - ' . $end;
                } else {
                    $v['photoshoot_slot'] = $booking->photoshoot_slot; // fallback
                }

                if ($start && $end && $end <= $start) {
                    return back()
                        ->withErrors(['end_time' => 'Jam akhir harus setelah jam mulai'])
                        ->withInput();
                }

                if ($end <= $start) {
                    return back()->withErrors(['end_time'=>'Jam akhir harus setelah jam mulai'])->withInput();
                }

                /* ============================
                KAPASITAS SLOT (2 STUDIO)
                ============================ */
                $kapasitas = 2;

                $overlap = BookingClient::where('id','!=',$booking->id)
                    ->whereDate('photoshoot_date',$effDate)
                    ->where(function($q) use ($start,$end){
                        $q->whereTime('start_time','<',$end)
                        ->whereTime('end_time','>',$start)
                        ->orWhere(function($q2) use ($start,$end){
                            $q2->whereNotNull('extra_start_time')
                                ->whereNotNull('extra_end_time')
                                ->whereTime('extra_start_time','<',$end)
                                ->whereTime('extra_end_time','>',$start);
                        });
                    })->count();

                if ($overlap >= $kapasitas) {
                    return back()->withErrors(['slot_code'=>'Slot sudah penuh'])->withInput();
                }

                /* ============================
                CEK TEMA UTAMA
                ============================ */
                $temaUtama = $v['tema_kode'] ?? $booking->tema_kode;

                if ($temaUtama) {
                    $dipakai = BookingClient::where('id','!=',$booking->id)
                        ->whereDate('photoshoot_date',$effDate)
                        ->where(function($q) use ($temaUtama){
                            $q->where('tema_kode',$temaUtama)
                            ->orWhere('tema2_kode',$temaUtama);
                        })
                        ->whereTime('start_time','<',$end)
                        ->whereTime('end_time','>',$start)
                        ->exists();

                    if ($dipakai) {
                        return back()->withErrors(['tema_kode'=>'Tema sudah dipakai di jam ini'])->withInput();
                    }
                }

                $tema2 = $v['tema2_kode'] ?? $booking->tema2_kode;

                if ($tema2) {
                    $dipakai2 = BookingClient::where('id','!=',$booking->id)
                        ->whereDate('photoshoot_date',$effDate)
                        ->where(function($q) use ($tema2){
                            $q->where('tema_kode',$tema2)
                            ->orWhere('tema2_kode',$tema2);
                        })
                        ->whereTime('start_time','<',$end)
                        ->whereTime('end_time','>',$start)
                        ->exists();

                    if ($dipakai2) {
                        return back()
                            ->withErrors(['tema2_kode'=>'Tema tambahan sudah dipakai di jam ini'])
                            ->withInput();
                    }
                }

                /* ============================
                ADDON
                ============================ */
                $addonIds = $request->input('addons', []);
                $addons = empty($addonIds) ? collect() : Addon::whereIn('id',$addonIds)->where('is_active',1)->get();

                $addonSlot = $addons->firstWhere('kategori',1);
                $addonTema = $addons->firstWhere('kategori',2);

                /* ===== EXTRA SLOT ===== */
                if ($addonSlot) {
                    $es = $v['extra_start_time'] ?? null;
                    $ee = $v['extra_end_time']   ?? null;

                    if (!$es || !$ee) {
                        return back()->withErrors(['extra_slot_code'=>'Extra slot dipilih tapi jam kosong'])->withInput();
                    }

                    if ($es < $end && $ee > $start) {
                        return back()->withErrors(['extra_slot_code'=>'Extra slot overlap slot utama'])->withInput();
                    }

                    $v['extra_photoshoot_slot'] = substr($es,0,5).' - '.substr($ee,0,5);
                    $v['extra_minutes'] = (int)$addonSlot->durasi;
                } else {
                    $v['extra_photoshoot_slot'] = null;
                    $v['extra_minutes'] = 0;
                }

                /* ============================
                HARGA
                ============================ */
                if (array_key_exists('package_id',$v)) {
                    $pkg = Package::findOrFail($v['package_id']);
                    $booking->package_price = $pkg->final_price;
                }

                $booking->addons_total = (int)$addons->sum('harga');
                $booking->grand_total = $booking->package_price + $booking->addons_total;

                /* ============================
                SAVE
                ============================ */
                foreach ($v as $k=>$val) {
                    $booking->{$k} = $val;
                }

                $booking->nama_gabungan  = ($booking->nama_cpp ?: 'CPP').' & '.($booking->nama_cpw ?: 'CPW');
                $booking->phone_gabungan = ($booking->phone_cpp ?: '').' & '.($booking->phone_cpw ?: '');

                $booking->save();
            }
            elseif ($section === 'skemakerja') {
                $booking = BookingClient::findOrFail($id);

                $validated = $request->validate([
                    'editor_karyawan_id'      => 'nullable|exists:data_diri_karyawan,id',
                    'photografer_karyawan_id' => 'nullable|exists:data_diri_karyawan,id',
                    'videografer_karyawan_id' => 'nullable|exists:data_diri_karyawan,id',
                    'makeup_karyawan_id'      => 'nullable|exists:data_diri_karyawan,id',
                    'attire_karyawan_id'      => 'nullable|exists:data_diri_karyawan,id',
                ]);

                SkemaKerja::updateOrCreate(
                    ['booking_client_id' => $booking->id],
                    [
                        'editor_karyawan_id'      => $validated['editor_karyawan_id'] ?? null,
                        'photografer_karyawan_id' => $validated['photografer_karyawan_id'] ?? null,
                        'videografer_karyawan_id' => $validated['videografer_karyawan_id'] ?? null,
                        'makeup_karyawan_id'      => $validated['makeup_karyawan_id'] ?? null,
                        'attire_karyawan_id'      => $validated['attire_karyawan_id'] ?? null,
                    ]
                );
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
                'bookingexecutive' => 'JadwalPesanan',

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
                'bookingexecutive' => BookingClient::findOrFail($id),
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
}
