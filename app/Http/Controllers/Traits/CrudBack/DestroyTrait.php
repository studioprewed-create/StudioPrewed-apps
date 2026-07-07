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

trait DestroyTrait {
    public function destroy(Request $request, $section, $id){
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
                'brand-category' => 'Brand.KategoriPartnership',
                'tacpackage' => 'Catalogue/LibraryCatalogue',
                'konsepattire' => 'Catalogue/LibraryCatalogue',
                'descpackage' => 'Catalogue/LibraryCatalogue',
                'packagelabel' => 'Catalogue/LibraryCatalogue',
            ];

            $redirectPage = $redirectMap[$section] ?? 'MenuPanel.HomePages.Dashboard';

            $item = match($section) {
                'slide' => HeroSlide::findOrFail($id),
                'marquee' => Marquee::findOrFail($id),
                'aboutus' => AboutUs::findOrFail($id),
                'service' => Service::findOrFail($id),
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

            if ($section === 'aboutus') {
                if (is_array($item->image)) {
                    foreach ($item->image as $img) {
                        if ($img && Storage::disk('public')->exists($img)) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                } else {
                    if ($item->image && Storage::disk('public')->exists($item->image)) {
                        Storage::disk('public')->delete($item->image);
                    }
                }

                $item->delete();
            }
            if (isset($item->image) && $item->image) {
                if (is_array($item->image)) {
                    foreach ($item->image as $img) {
                        if ($img && Storage::disk('public')->exists($img)) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                } else {
                    if (Storage::disk('public')->exists($item->image)) {
                        Storage::disk('public')->delete($item->image);
                    }
                }
            }

            if (isset($item->avatar) && $item->avatar && Storage::disk('public')->exists($item->avatar)) {
                Storage::disk('public')->delete($item->avatar);
            }
            elseif ($section === 'user') {
                $item->delete();
            }
            $item->delete();

            return redirect()->route('executive.page', ['page' => $redirectPage])
                        ->with('success', ucfirst($section).' berhasil diperbarui!');
    }
}