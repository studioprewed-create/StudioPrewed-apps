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

trait PortfolioTrait {
    public function Portofolio(Request $request)
        {
            $slides  = HeroSlide::where('active',1)->orderBy('order')->get();
            $category = $request->query('category');
            $allowedCategories = ['prewed','family','maternity','postwedding','beauty','birthday'];
            if (!in_array($category, $allowedCategories, true)) {
                $category = null;
            }
            $galleries = GalleryItem::where('active',1)->orderBy('order')->get();

            return view('HOMEPAGES.PAGE.Portofolio', [
                'slides'           => $slides,
                'galleries'        => $galleries,
                'selectedCategory' => $category,
            ]);
        }
}