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

trait UpdateTrait {
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
                'brand-category' => BrandCategory::findOrFail($id),
                'tacpackage' => TACPackage::findOrFail($id),
                'konsepattire' => KonsepAttire::findOrFail($id),
                'descpackage' => DESCPackage::findOrFail($id),
                'packagelabel' => PackageLabel::findOrFail($id),
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
                'package'   => 'Catalogue/Package',
                'temabaju'  => 'Catalogue',
                'bookingexecutive' => 'JadwalPesanan',
                'skemakerja' => 'JadwalKerja',
                'brand-category' => 'Brand.KategoriPartnership',
                'tacpackage' => 'Catalogue/LibraryCatalogue',
                'konsepattire' => 'Catalogue/LibraryCatalogue',
                'descpackage' => 'Catalogue/LibraryCatalogue',
                'packagelabel' => 'Catalogue/LibraryCatalogue',
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

                    'data_brand.logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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
                    $user->dataBrand()?->delete();
                }
                elseif ( $user->role === 'BRAND_PARTNERSHIP' || $user->role === 'STUDIO' ) { 
                    if ($request->filled('data_brand')) { 
                        $data = $request->input('data_brand'); 
                        
                        if (empty($data['nama_brand'])) { 
                            $data['nama_brand'] = $user->name; 
                            }
                        if ($request->hasFile('data_brand.logo')) { 
                            $path = $request ->file('data_brand.logo') 
                            ->store('brand/logo', 'public'); 
                            $data['logo'] = $path; 
                        }
                            $user->dataBrand()->updateOrCreate( 
                                ['user_id' => $user->id], $data ); 
                    } 
                    $user->dataDiri()?->delete(); 
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
                    $user->dataBrand()?->delete();
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

                $imagePath = $item->images;

                if ($request->hasFile('images')) {
                    if ($imagePath) Storage::disk('public')->delete($imagePath);
                    $imagePath = $request->file('images')->store('packages', 'public');
                }

                $item->update([
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
            elseif ($section === 'brand-category') {

                $item = BrandCategory::findOrFail($id);

                $request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string',
                ]);

                $item->update([
                    'name' => $request->name,
                    'description' => $request->description,
                ]);
            }
            elseif ($section === 'tacpackage') {

                $item = TACPackage::findOrFail($id);

                $validated = $request->validate([
                    'content' => 'required|string',
                ]);

                $item->update([
                    'content' => $validated['content'],
                    'active'  => $request->boolean('active', $item->active),
                ]);
            }
            elseif ($section === 'konsepattire') {

                $item = KonsepAttire::findOrFail($id);

                $validated = $request->validate([
                    'content' => 'required|string',
                ]);

                $item->update([
                    'content' => $validated['content'],
                    'active'  => $request->boolean('active', $item->active),
                ]);
            }
            elseif ($section === 'descpackage') {
                $item = DESCPackage::findOrFail($id);

                $validated = $request->validate([
                    'content' => 'required|string',
                ]);

                $item->update([
                    'content' => $validated['content'],
                    'active' => $request->boolean('active', $item->active),
                ]);
            }
            elseif ($section === 'packagelabel') {
                $item = PackageLabel::findOrFail($id);

                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                ]);

                $item->update([
                    'name' => $validated['name'],
                    'active' => $request->boolean('active', $item->active),
                ]);
            }
            return redirect()->route('executive.page', ['page' => $redirectPage])
                        ->with('success', ucfirst($section).' berhasil diperbarui!');
        }
}