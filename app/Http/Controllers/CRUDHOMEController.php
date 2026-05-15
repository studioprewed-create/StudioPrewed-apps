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

class CRUDHOMEController extends Controller
{
    public function SurveyStore(Request $request)
        {
            $validated = $request->validate([

                'customer_name' => 'nullable|string|max:255',
                'photo_date' => 'nullable|date',
                'favorite_services' => 'nullable|array',
                'favorite_services.*' => 'string',
                'recommendation_score' => 'required|integer|min:1|max:10',
                'feedback' => 'nullable|string',
            ]);

            Survey::create([

                'customer_name' => $validated['customer_name'] ?? null,
                'photo_date' => $validated['photo_date'] ?? null,
                'favorite_services' => $validated['favorite_services'] ?? [],
                'recommendation_score' => $validated['recommendation_score'],
                'feedback' => $validated['feedback'] ?? null,
            ]);

            return redirect()
                ->route('homepage')
                ->with('success', 'Terima kasih sudah mengisi survey.');
        }
    public function storeAccount(Request $request)
        {
            $user = $request->user();

            // Cegah double data diri
            if ($user->dataDiri) {
                return redirect()
                    ->route('Account')
                    ->with('warning', 'Data diri sudah ada, silakan update.');
            }

            $validated = $request->validate([
                'nama'                   => 'required|string|max:255',
                'phone'                  => 'nullable|string|max:20',
                'jenis_kelamin'          => 'nullable|in:laki-laki,perempuan',
                'tanggal_lahir'          => 'nullable|date',
                'tanggal_pernikahan'     => 'nullable|date',
                'nama_pasangan'          => 'nullable|string|max:255',
                'phone_pasangan'         => 'nullable|string|max:20',
                'jenis_kelamin_pasangan' => 'nullable|in:laki-laki,perempuan',
                'tanggal_lahir_pasangan' => 'nullable|date',
            ]);

            $validated['user_id'] = $user->id;

            DataDiri::create($validated);

            return redirect()
                ->route('Account')
                ->with('success', 'Data diri berhasil disimpan.');
        }

    public function updateAccount(Request $request, $id)
        {
            $user     = $request->user();
            $dataDiri = DataDiri::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $validated = $request->validate([
                'nama'                   => 'required|string|max:255',
                'phone'                  => 'nullable|string|max:20',
                'jenis_kelamin'          => 'nullable|in:laki-laki,perempuan',
                'tanggal_lahir'          => 'nullable|date',
                'tanggal_pernikahan'     => 'nullable|date',
                'nama_pasangan'          => 'nullable|string|max:255',
                'phone_pasangan'         => 'nullable|string|max:20',
                'jenis_kelamin_pasangan' => 'nullable|in:laki-laki,perempuan',
                'tanggal_lahir_pasangan' => 'nullable|date',
            ]);

            $dataDiri->update($validated);

            return redirect()
                ->route('Account')
                ->with('success', 'Data diri berhasil diperbarui.');
        }

    public function destroyAccount($id)
        {
            $dataDiri = DataDiri::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $dataDiri->delete();

            return redirect()
                ->route('Account')
                ->with('success', 'Data diri berhasil dihapus.');
        }
    public function storeReview(Request $request)
        {
            if (!Auth::check()) {
                return redirect()->route('login')->withErrors('Anda harus login untuk menambahkan review.');
            }

            // Validasi input yang disediakan form publik
            $validated = $request->validate([
                'rating'  => 'required|integer|min:1|max:5',
                'avatar'  => 'nullable|image|max:5120',
                'content' => 'nullable|string',
            ]);

            // Overwrite/isi otomatis dari user login (abaikan hidden input dari form kalau ada)
            $user = Auth::user();
            $validated['name'] = $user->name ?? 'User';
            $validated['role'] = $user->role ?? 'CLIENT';
            $validated['date'] = now()->toDateString();

            if ($request->hasFile('avatar')) {
                $validated['avatar'] = $request->file('avatar')->store('homepage', 'public');
            }
            $review = new Review($validated);
            $review->active = true;
            $review->save();

            return redirect()->route('homepage')->with('success', 'Berhasil ditambahkan!');
        }
}
