<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\Frontpage\DashboardTrait;
use App\Http\Controllers\Traits\Frontpage\PortfolioTrait;
use App\Http\Controllers\Traits\Frontpage\PricelistTrait;
use App\Http\Controllers\Traits\Frontpage\CatalogTrait;
use App\Http\Controllers\Traits\Frontpage\SurveyTrait;
use App\Http\Controllers\Traits\Frontpage\AccountTrait;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Database\QueryException;
// use Illuminate\Support\Str;
// use App\Helpers\SlotHelper;
// use App\Models\User;
// use App\Models\Package;
// use App\Models\TemaBaju;
// use App\Models\HeroSlide;
// use App\Models\Marquee;
// use App\Models\AboutUs;
// use App\Models\Service;
// use App\Models\Review;
// use App\Models\HeroContent;
// use App\Models\Faq;
// use App\Models\GalleryItem;
// use App\Models\PromoBanner;
// use App\Models\BookingClient;
// use App\Models\Addon;
// use App\Models\BookingAddon;
// use App\Models\DataDiri;
// use App\Models\DataDiriKaryawan;
// use \App\Models\SkemaKerja;
// use \App\Models\Survey;
// use App\Models\GoogleReview;

class FRONTPAGEController extends Controller
{
    // public function Survey()
    //     {
    //         return view('HOMEPAGES.FITUR.Survey');
    //     }

    // public function index(Request $request)
    //     {
    //         $slides  = HeroSlide::where('active',1)->orderBy('order')->get();
    //         $marquees = Marquee::where('active',1)->orderBy('order')->get();
    //         $aboutus = AboutUs::active()->orderBy('order')->get();
    //         $model1 = AboutUs::active()->where('model_type', 'model1')->orderBy('order')->get();
    //         $model2 = AboutUs::active()->where('model_type', 'model2')->orderBy('order')->get();
    //         $model3 = AboutUs::active()->where('model_type', 'model3')->orderBy('order')->get();
    //         $reviews = Review::where('active',1)->latest()->get();
    //         $heroes  = HeroContent::where('active',1)->orderBy('order')->get();
    //         $faqs    = Faq::where('active',1)->orderBy('order')->get();
    //         $portraitServices = Service::active()->orderBy('order')->get();
    //         $googleReviews = GoogleReview::query()->orderByRaw("
    //             CASE
    //                 WHEN review_images IS NOT NULL
    //                 AND review_images != ''
    //                 THEN 0
    //                 ELSE 1
    //             END
    //         ")

    //         ->orderByDesc('rating')
    //         ->orderByDesc('review_date')
    //         ->get();
    //         return view('HOMEPAGES.PAGE.Dashboard', compact('slides', 'marquees','aboutus','model1', 'model2', 'model3', 'reviews','heroes','faqs','portraitServices','googleReviews'));
    //     }
    // public function Portofolio(Request $request)
    //     {
    //         $slides  = HeroSlide::where('active',1)->orderBy('order')->get();
    //         $category = $request->query('category');
    //         $allowedCategories = ['prewed','family','maternity','postwedding','beauty','birthday'];
    //         if (!in_array($category, $allowedCategories, true)) {
    //             $category = null;
    //         }
    //         $galleries = GalleryItem::where('active',1)->orderBy('order')->get();

    //         return view('HOMEPAGES.PAGE.Portofolio', [
    //             'slides'           => $slides,
    //             'galleries'        => $galleries,
    //             'selectedCategory' => $category,
    //         ]);
    //     }
    // public function Account(Request $request)
    //     {
    //         $user = $request->user(); // sama dengan Auth::user()

    //         $slides   = HeroSlide::where('active',1)->orderBy('order')->get();
    //         $dataDiri = $user->dataDiri; // lewat relasi

    //         $bookings = BookingClient::with(['package']) // opsional kalau ada relasi package()
    //         ->where('user_id', $user->id)
    //         ->orderByDesc('created_at')
    //         ->get();

    //         return view('HOMEPAGES.PAGE.Account', [
    //             'slides'   => $slides,
    //             'user'     => $user,
    //             'dataDiri' => $dataDiri,
    //             'bookings' => $bookings,
    //         ]);
    //     }
    // public function Pricelist()
    //     {
    //         $slides  = HeroSlide::where('active',1)->orderBy('order')->get();
    //         $promos  = PromoBanner::where('active',1)->orderBy('order')->get();
    //         $packages= Package::all();
    //         $temas   = TemaBaju::where('active',1)->orderBy('order')->get();
    //         $addons   = Addon::active()->orderBy('kategori')->get();

    //         $dataDiri = null;

    //         if (Auth::check()) {
    //             $dataDiri = DataDiri::where('user_id', Auth::id())->first();
    //         }
    //         return view('HOMEPAGES.PAGE.Pricelist', compact('slides','promos','packages','temas','addons','dataDiri'));
    //     }
    // public function Katalog()
    //     {
    //         $slides  = HeroSlide::where('active',1)->orderBy('order')->get();
    //         $temas   = TemaBaju::where('active',1)->orderBy('order')->get();

    //         $temas->map(function($t){
    //             $t->slug = Str::slug($t->nama);
    //             return $t;
    //         });

    //         $filters = $temas
    //             ->pluck('nama')
    //             ->filter()
    //             ->unique()
    //             ->mapWithKeys(function ($nama) {
    //                 return [$nama => Str::slug($nama)];
    //         });


    //         return view('HOMEPAGES.PAGE.Katalog', compact('slides','temas','filters'));
    //     }

    use DashboardTrait;
    use PortfolioTrait;
    use PricelistTrait;
    use CatalogTrait;
    use AccountTrait;
    use SurveyTrait;
}