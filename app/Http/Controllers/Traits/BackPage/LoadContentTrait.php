<?php

namespace App\Http\Controllers\Traits\BackPage;

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
use App\Models\TACPackage;
use App\Models\KonsepAttire;
use App\Models\DESCPackage;
use App\Models\PackageLabel;
use App\Models\AttireCode;
use App\Models\DataBrand;


trait LoadContentTrait {
     public function loadContent(Request $request, $page)
        {
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
            if ($page === 'Catalogue.LibraryCatalogue') {
                $attireCodes = AttireCode::query()
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get();

                $tacPackages = TACPackage::query()
                    ->orderBy('id')
                    ->get();

                $konsepAttires = KonsepAttire::query()
                    ->orderBy('id')
                    ->get();

                $descPackages = DESCPackage::query()
                    ->orderBy('id')
                    ->get();

                $packageLabels = PackageLabel::query()
                    ->orderBy('id')
                    ->get();

                $temas = TemaBaju::query()
                    ->with([
                        'codeMaster',
                        'designerBrand.category',
                        'tipeAttire',
                        'details',
                    ])
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get();

                $attireBrands = DataBrand::query()
                    ->with('category')
                    ->where('is_active', true)
                    ->whereHas('category', function ($query) {
                        $query->where('name', 'Attire Partner');
                    })
                    ->orderBy('nama_brand')
                    ->get();

                return view(
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$page",
                    compact(
                        'attireCodes',
                        'tacPackages',
                        'konsepAttires',
                        'descPackages',
                        'packageLabels',
                        'temas',
                        'attireBrands'
                    )
                );
            }
            if ($page === 'Catalogue.Package') {
                $packages = Package::query()
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get();

                $temas = TemaBaju::query()
                    ->with([
                        'codeMaster',
                        'designerBrand.category',
                        'tipeAttire',
                        'details',
                    ])
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get();

                $attireCodes = AttireCode::query()
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get();

                $attireBrands = DataBrand::query()
                    ->with('category')
                    ->where('is_active', true)
                    ->whereHas('category', function ($query) {
                        $query->where('name', 'Attire Partner');
                    })
                    ->orderBy('nama_brand')
                    ->get();

                $tacPackages = TACPackage::query()
                    ->orderBy('id')
                    ->get();

                $konsepAttires = KonsepAttire::query()
                    ->orderBy('id')
                    ->get();

                $descPackages = DESCPackage::query()
                    ->orderBy('id')
                    ->get();

                $packageLabels = PackageLabel::query()
                    ->orderBy('id')
                    ->get();

                return view(
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$page",
                    compact(
                        'packages',
                        'temas',
                        'attireCodes',
                        'attireBrands',
                        'tacPackages',
                        'konsepAttires',
                        'descPackages',
                        'packageLabels'
                    )
                );
            }
            if ($page === 'Catalogue.TemaBaju') {
                $packages = Package::query()
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get();

                $temas = TemaBaju::query()
                    ->with([
                        'codeMaster',
                        'designerBrand.category',
                        'tipeAttire',
                        'details',
                    ])
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get();

                $attireCodes = AttireCode::query()
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get();

                $attireBrands = DataBrand::query()
                    ->with('category')
                    ->where('is_active', true)
                    ->whereHas('category', function ($query) {
                        $query->where('name', 'Attire Partner');
                    })
                    ->orderBy('nama_brand')
                    ->get();

                $tacPackages = TACPackage::query()
                    ->orderBy('id')
                    ->get();

                $konsepAttires = KonsepAttire::query()
                    ->orderBy('id')
                    ->get();

                $descPackages = DESCPackage::query()
                    ->orderBy('id')
                    ->get();

                $packageLabels = PackageLabel::query()
                    ->orderBy('id')
                    ->get();

                return view(
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$page",
                    compact(
                        'packages',
                        'temas',
                        'attireCodes',
                        'attireBrands',
                        'tacPackages',
                        'konsepAttires',
                        'descPackages',
                        'packageLabels'
                    )
                );
            }
            if ($page === 'Schedule.JadwalPesanan') {
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
            if ($page === 'Schedule.JadwalKerja') {

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
            if (view()->exists("OPERATIONALPAGES.FITUR.MAINCONTENT.$page")) {
                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$page");
            }

            return "<div class='alert alert-warning'>Halaman <b>$page</b> belum dibuat.</div>";
        }
}