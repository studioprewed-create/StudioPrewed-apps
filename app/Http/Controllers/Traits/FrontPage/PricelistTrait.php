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

trait PricelistTrait {
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
}