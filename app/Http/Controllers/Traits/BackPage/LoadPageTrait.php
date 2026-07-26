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


trait LoadPageTrait {
     private function loadPage(Request $request, $page)
        {
            if ($request->ajax()) {
                return $this->loadContent($request, $page);
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

                /*
                |--------------------------------------------------------------------------
                | ModalCatalogue digunakan bersama
                |--------------------------------------------------------------------------
                */

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

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page'           => $page,
                    'attireCodes'    => $attireCodes,
                    'tacPackages'    => $tacPackages,
                    'konsepAttires'  => $konsepAttires,
                    'descPackages'   => $descPackages,
                    'packageLabels'  => $packageLabels,
                    'temas'          => $temas,
                    'attireBrands'   => $attireBrands,
                ]);
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

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page'           => $page,
                    'packages'       => $packages,
                    'temas'          => $temas,
                    'attireCodes'    => $attireCodes,
                    'attireBrands'   => $attireBrands,
                    'tacPackages'    => $tacPackages,
                    'konsepAttires'  => $konsepAttires,
                    'descPackages'   => $descPackages,
                    'packageLabels'  => $packageLabels,
                ]);
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

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page'           => $page,
                    'packages'       => $packages,
                    'temas'          => $temas,
                    'attireCodes'    => $attireCodes,
                    'attireBrands'   => $attireBrands,
                    'tacPackages'    => $tacPackages,
                    'konsepAttires'  => $konsepAttires,
                    'descPackages'   => $descPackages,
                    'packageLabels'  => $packageLabels,
                ]);
            }
            if ($page === 'Schedule.JadwalPesanan') {
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

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page'           => $page,
                    'startOfWeek'    => $startOfWeek,
                    'bookingsByDate' => $bookingsByDate,
                    'karyawanByRole' => $karyawanByRole,
                ]);
            }
            return view('OPERATIONALPAGES.PAGE.EXECUTIVE', ['page' => $page]);
        }

}