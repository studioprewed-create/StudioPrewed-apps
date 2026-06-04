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

class SUBEXECUTIVEController extends Controller
{
    public function profile(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Profile');}
    public function appearance(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Appearance');}
    public function notification(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Notification');}
    public function activity(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Activity');}

    public function statistiksurvey(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikSurvey'); }
    public function statistikreview(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikReview'); }
    public function statistikpengeluaran(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikPengeluaran'); }
    public function statistikpendapatan(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikPendapatan'); }
    public function statistikkinerja(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikKinerja'); }
    public function statistikkatalog(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikKatalog'); }

    public function dataakun(Request $request){ return $this->subLoadPage($request, 'Management', 'Management.DataAkun'); }
    public function dataPartnership(Request $request){ return $this->subLoadPage($request, 'Management', 'Management.Partnership'); }
    public function kategoriPartnership(Request $request){ return $this->subLoadPage($request, 'Management', 'Management.KPartnership'); }

    private function subLoadPage(Request $request, $page, $subpage)
        {
            if ($request->ajax()) {
                return $this->subLoadContent($request, $page, $subpage);
            }
            if ($subpage === 'StatistikContent.StatistikSurvey') {

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
                    'subpage' => $subpage,
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
            if ($subpage === 'StatistikContent.StatistikReview') {
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
                        'subpage' => $subpage,
                        'googleReviews' => $googleReviews,
                        'sort' => $sort,
                    ]
                );
            }
            if ($subpage === 'Management.DataAkun') {
                $users = User::with(['dataDiri', 'dataDiriKaryawan','dataBrand'])->get();

                $brandCategories = BrandCategory::all();

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page' => $page,
                    'subpage' => $subpage,
                    'users' => $users,
                    'brandCategories' => $brandCategories,
                ]);
            }
            if ($subpage === 'Management.KPartnership') {
                $brandCategories = BrandCategory::latest()->get();
                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page' => $page,
                    'subpage' => $subpage,
                    'brandCategories' => $brandCategories,
                ]);
            }
            if ($subpage === 'Management.Partnership') {

                $brands = User::with([
                    'dataBrand',
                    'dataBrand.category',
                ])
                ->whereIn('role', [
                    'BRAND_PARTNERSHIP',
                    'STUDIO',
                ])
                ->latest()
                ->get();

                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                    'page'   => $page,
                    'subpage' => $subpage,
                    'brands' => $brands,
                ]);
            }
            return view('OPERATIONALPAGES.PAGE.EXECUTIVE', ['page' => $page, 'subpage' => $subpage]);
        }
    public function subLoadContent(Request $request, $page, $subpage)
        {
            if ($subpage === 'StatistikContent.StatistikSurvey') {

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
                $surveys = $allDataRaw;
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
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage",
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
            if ($subpage === 'StatistikContent.StatistikReview') {
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
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage",
                    compact('googleReviews', 'sort')
                );
            }
            if ($subpage === 'Management.DataAkun') {
                $users = User::with(['dataDiri', 'dataDiriKaryawan','dataBrand'])->get();

                $brandCategories = BrandCategory::all();

                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage", compact('users', 'brandCategories'));
            }
            if ($subpage === 'Management.KPartnership') {

                $brandCategories = BrandCategory::latest()->get();
                return view(
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage",
                    compact('brandCategories')
                );
            }
            if ($subpage === 'Management.Partnership') {

                $brands = User::with([
                    'dataBrand.category'
                ])
                ->whereIn('role', [
                    'BRAND_PARTNERSHIP',
                    'STUDIO'
                ])
                ->get();

                return view(
                    "OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage",
                    compact('brands')
                );
            }
            if (view()->exists("OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage")) {
                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage");
            }

            return "<div class='alert alert-warning'>Halaman <b>$subpage</b> belum dibuat.</div>";
        }
    public function subLoadDirect(Request $request, $page, $subpage)
        {
            if (view()->exists("OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage")) {
                if ($subpage === 'StatistikContent.StatistikSurvey') {

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
                        'subpage' => $subpage,
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
                if ($subpage === 'StatistikContent.StatistikReview') {

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
                            'subpage' => $subpage,
                            'googleReviews' => $googleReviews,
                            'sort' => $sort,
                        ]
                    );
                }
                if ($subpage === 'Management.DataAkun') {
                    $users = User::with(['dataDiri', 'dataDiriKaryawan','dataBrand'])->get();

                    $brandCategories = BrandCategory::all();

                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                        'subpage' => $subpage,
                        'users' => $users,
                        'brandCategories' => $brandCategories,
                    ]);
                }
                if ($subpage === 'Management.KPartnership') {
                    $brandCategories = BrandCategory::latest()->get();
                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page' => $page,
                        'subpage' => $subpage,
                        'brandCategories' => $brandCategories,
                    ]);
                }
                if ($subpage === 'Management.Partnershipz') {

                    $brands = User::with([
                        'dataBrand',
                        'dataBrand.category',
                    ])
                    ->whereIn('role', [
                        'BRAND_PARTNERSHIP',
                        'STUDIO',
                    ])
                    ->latest()
                    ->get();

                    return view('OPERATIONALPAGES.PAGE.EXECUTIVE', [
                        'page'   => $page,
                        'subpage' => $subpage,
                        'brands' => $brands,
                    ]);
                }
                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', ['page' => $page, 'subpage' => $subpage]);
            }
            abort(404, "Halaman $subpage tidak ditemukan");
        }
}