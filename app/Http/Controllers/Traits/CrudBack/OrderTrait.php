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

trait OrderTrait {
    private function nextOrder(string $modelClass): int
        {
            $max = $modelClass::max('order');
            return is_null($max) ? 0 : ((int)$max + 1);
        }

    private function cleanOrder($raw, string $modelClass, $fallback = null): int
        {
            if (is_numeric($raw)) return (int)$raw;
            if (is_numeric($fallback)) return (int)$fallback;
            return $this->nextOrder($modelClass);
        }
}