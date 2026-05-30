<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EXECUTIVEController;
use App\Http\Controllers\SUBEXECUTIVEController;
use App\Http\Controllers\FRONTPAGEController;
use App\Http\Controllers\CRUDHOMEController;
use App\Http\Controllers\CRUDBACKController;
use App\Http\Controllers\BookingHOMEController;
use App\Helpers\SlotHelper;



Route::get('/', [FRONTPAGEController::class, 'index'])->name('homepage');
Route::get('/Portofolio', [FRONTPAGEController::class, 'Portofolio'])->name('Portofolio');
Route::get('/Pricelist', [FRONTPAGEController::class, 'Pricelist'])->name('Pricelist');
Route::get('/Katalog', [FRONTPAGEController::class, 'Katalog'])->name('Katalog');
Route::get('/Survey', [FRONTPAGEController::class, 'Survey'])->name('Survey');
Route::post('/Survey/store', [CRUDHOMEController::class, 'SurveyStore'])->name('SurveyStore');

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'verify'])->name('login.verify');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/Registrasi', [AuthController::class, 'Registrasi'])->name('Registrasi');
Route::post('/review/store', [CRUDHOMEController::class, 'storeReview'])->name('review.store')->middleware('auth');

Route::get('/api/slots', [EXECUTIVEController::class, 'apiSlots'])->name('api.slots');
Route::get('/api/tema-by-name', [EXECUTIVEController::class, 'getTemaByName'])->name('api.temaByName');
Route::post('/booking-client', [EXECUTIVEController::class, 'bookingClientStore'])->name('bookingClient.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        if (in_array($role, ['ADMIN', 'DIREKTUR', 'CREATIVE_DIRECTOR', 'MANAGER'])) {
            return redirect()->route('executive.dashboard');
        } elseif ($role === 'MARKETING') {
            return view('OPERATIONALPAGES.PAGE.MARKETINGHOME');
        } elseif ($role === 'ADMIN_ATTIRE') {
            return view('OPERATIONALPAGES.PAGE.ADMINATTIREHOME');
        } elseif ($role === 'STYLISH') {
            return view('OPERATIONALPAGES.PAGE.STYLISHHOME');
        } elseif ($role === 'FITTER') {
            return view('OPERATIONALPAGES.PAGE.FITTERHOME');
        } elseif ($role === 'BRAND_PARTNERSHIP') {
            return view('OPERATIONALPAGES.PAGE.BRANDPARTNERSHIPHOME');
        } elseif ($role === 'STUDIO') {
            return view('OPERATIONALPAGES.PAGE.STUDIOHOME');
        } elseif ($role === 'CONTENT_CREATOR') {
            return view('OPERATIONALPAGES.PAGE.CONTENTHOME');
        } elseif ($role === 'CLIENT') {
            return app(FRONTPAGEController::class)->index(request());
        } else {
            return view('OPERATIONALPAGES.PAGE.TEAMTIVE');
        }
    })->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::get('/account', [FRONTPAGEController::class, 'Account'])
            ->name('Account');
    });

    Route::middleware(['auth', 'role:CLIENT'])->group(function () {
        Route::post('/account', [CRUDHOMEController::class, 'storeAccount'])->name('Account.store');
        Route::put('/account/{id}', [CRUDHOMEController::class, 'updateAccount'])->name('Account.update');
        Route::delete('/account/{id}', [CRUDHOMEController::class, 'destroyAccount'])->name('Account.destroy');
    });


    Route::prefix('executive')->name('executive.')->group(function () {
        Route::get('/dashboard', [EXECUTIVEController::class, 'dashboard'])->name('dashboard');
        Route::get('/catalogue', [EXECUTIVEController::class, 'catalogue'])->name('catalogue');
        Route::get('/galleryattire', [EXECUTIVEController::class, 'galleryattire'])->name('galleryattire');
        Route::get('/statistik', [EXECUTIVEController::class, 'statistik'])->name('statistik');
        Route::get('/Management', [EXECUTIVEController::class, 'management'])->name('management');
        Route::get('/upload', [EXECUTIVEController::class, 'upload'])->name('upload');
        Route::get('/settings', [EXECUTIVEController::class, 'settings'])->name('settings');
        Route::get('/schedule', [EXECUTIVEController::class, 'schedule'])->name('schedule');
        
        Route::prefix('Catalogue')->name('Catalogue.')->group(function () {
            Route::get('/TACPackage', [EXECUTIVEController::class, 'TACPackage'])->name('tacpackage');
        });

        Route::prefix('menupanel')->name('menupanel.')->group(function () {
            Route::get('/homepages/dashboard', [EXECUTIVEController::class, 'menuHomeDashboard'])->name('homepages.dashboard');
            Route::get('/homepages/portofolio', [EXECUTIVEController::class, 'menuHomePortofolio'])->name('homepages.portofolio');
            Route::get('/homepages/pricelist', [EXECUTIVEController::class, 'menuHomePricelist'])->name('homepages.pricelist');
            Route::get('/berkas', [EXECUTIVEController::class, 'menuBerkas'])->name('berkas');
        });

        Route::prefix('subpage')->name('subpage.')->group(function () {
            Route::get('/profile',[SUBEXECUTIVEController::class, 'profile'])->name('profile');
            Route::get('/appearance',[SUBEXECUTIVEController::class, 'appearance'])->name('appearance');
            Route::get('/notification',[SUBEXECUTIVEController::class, 'notification'])->name('notification');
            Route::get('/activity',[SUBEXECUTIVEController::class, 'activity'])->name('activity');

            Route::get('/statistiksurvey', [SUBEXECUTIVEController::class, 'statistiksurvey'])->name('statistiksurvey');
            Route::get('/statistikreview', [SUBEXECUTIVEController::class, 'statistikreview'])->name('statistikreview');
            Route::get('/statistikpengeluaran', [SUBEXECUTIVEController::class, 'statistikpengeluaran'])->name('statistikpengeluaran');
            Route::get('/statistikpendapatan', [SUBEXECUTIVEController::class, 'statistikpendapatan'])->name('statistikpendapatan');
            Route::get('/statistikkinerja', [SUBEXECUTIVEController::class, 'statistikkinerja'])->name('statistikkinerja');
            Route::get('/statistikkatalog', [SUBEXECUTIVEController::class, 'statistikkatalog'])->name('statistikkatalog');

            Route::get('/dataakun', [SUBEXECUTIVEController::class, 'dataakun'])->name('dataakun');
            Route::get('/DataPartnership', [SUBEXECUTIVEController::class, 'DataPartnership'])->name('dataPartnership');
            Route::get('/KategoriPartnership', [SUBEXECUTIVEController::class, 'KategoriPartnership'])->name('kategoriPartnership');

            Route::get('/Package', [SUBEXECUTIVEController::class, 'Package'])->name('package');
            Route::get('/TemaBaju', [SUBEXECUTIVEController::class, 'TemaBaju'])->name('temaBaju');
            Route::get('/Kategoritemabaju', [SUBEXECUTIVEController::class, 'Kategoritemabaju'])->name('kategoritemabaju');

            Route::get('/jadwalkerja', [SUBEXECUTIVEController::class, 'jadwalkerja'])->name('jadwalkerja');
            Route::get('/jadwalpesanan', [SUBEXECUTIVEController::class, 'jadwalpesanan'])->name('jadwalpesanan');
        });

        // AJAX load (tanpa view utama)
        Route::get('/load-content/{page}', [EXECUTIVEController::class, 'loadContent'])->name('load');
        Route::get('/{page}', [EXECUTIVEController::class, 'loadDirect'])->where('page', '[A-Za-z0-9\.\-]+')->name('page');

        Route::get('/load-sub-content/{page}/{subpage}',[SUBEXECUTIVEController::class, 'subLoadContent'])->where(['page' => '[A-Za-z0-9\.\-]+','subpage' => '[A-Za-z0-9\.\-]+'])->name('sub.load');
        Route::get('/sub/{page}/{subpage}',[SUBEXECUTIVEController::class, 'subLoadDirect'])->where(['page' => '[A-Za-z0-9\.\-]+','subpage' => '[A-Za-z0-9\.\-]+'])->name('sub.page');

        Route::prefix('homepages')->name('homepages.')->group(function () {
            Route::get('create/{section}', [CRUDBACKController::class, 'create'])->name('create');
            Route::post('store/{section}', [CRUDBACKController::class, 'store'])->name('store');
            Route::post('storePoerto/{section}', [CRUDBACKController::class, 'storePorto'])->name('storePorto');
            Route::get('edit/{section}/{id}', [CRUDBACKController::class, 'edit'])->name('edit');
            Route::get('editPorto/{section}/{id}', [CRUDBACKController::class, 'editPorto'])->name('editPorto');
            Route::put('update/{section}/{id}', [CRUDBACKController::class, 'update'])->name('update');
            Route::put('updatePorto/{section}/{id}', [CRUDBACKController::class, 'updatePorto'])->name('updatePorto');
            Route::delete('destroy/{section}/{id}', [CRUDBACKController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::post('/', [EXECUTIVEController::class, 'storeUser'])->name('store');         // create
            Route::put('/{id}', [EXECUTIVEController::class, 'updateUser'])->name('update');    // edit
            Route::delete('/{id}', [EXECUTIVEController::class, 'destroyUser'])->name('destroy'); // delete
        });

        Route::prefix('tema-baju')->name('tema_baju.')->group(function () {
            Route::post('/', [EXECUTIVEController::class, 'storeTemaBaju'])->name('store');
            Route::put('/{temaBaju}', [EXECUTIVEController::class, 'updateTemaBaju'])->name('update');
            Route::delete('/{temaBaju}', [EXECUTIVEController::class, 'destroyTemaBaju'])->name('destroy');
        });

         Route::prefix('packages')->name('packages.')->group(function () {
            Route::post('/', [EXECUTIVEController::class, 'storePackage'])->name('store');
            Route::put('/{package}', [EXECUTIVEController::class, 'updatePackage'])->name('update');
            Route::delete('/{package}', [EXECUTIVEController::class, 'destroyPackage'])->name('destroy');
        });
    });

});
