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

trait CatalogTrait {
    public function Katalog()
        {
            $slides  = HeroSlide::where('active',1)->orderBy('order')->get();
            $temas   = TemaBaju::where('active',1)->orderBy('order')->get();

            $temas->map(function($t){
                $t->slug = Str::slug($t->nama);
                return $t;
            });

            $filters = $temas
                ->pluck('nama')
                ->filter()
                ->unique()
                ->mapWithKeys(function ($nama) {
                    return [$nama => Str::slug($nama)];
            });


            return view('HOMEPAGES.PAGE.Katalog', compact('slides','temas','filters'));
        }
}