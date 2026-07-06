<?php

namespace App\Http\Controllers\Traits\FrontPage;

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

trait DashboardTrait {
    public function index(Request $request)
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
            $googleReviews = GoogleReview::query()->orderByRaw("
                CASE
                    WHEN review_images IS NOT NULL
                    AND review_images != ''
                    THEN 0
                    ELSE 1
                END
            ")

            ->orderByDesc('rating')
            ->orderByDesc('review_date')
            ->get();
            return view('HOMEPAGES.PAGE.Dashboard', compact('slides', 'marquees','aboutus','model1', 'model2', 'model3', 'reviews','heroes','faqs','portraitServices','googleReviews'));
        }
}