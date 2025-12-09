<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EXECUTIVEController;
use App\Helpers\SlotHelper;



Route::get('/', [EXECUTIVEController::class, 'index'])->name('homepage');
Route::get('/Portofolio', [EXECUTIVEController::class, 'Portofolio'])->name('Portofolio');
Route::get('/Pricelist', [EXECUTIVEController::class, 'Pricelist'])->name('Pricelist');



Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'verify'])->name('login.verify');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/Registrasi', [AuthController::class, 'Registrasi'])->name('Registrasi');
Route::post('/review/store', [EXECUTIVEController::class, 'storeReview'])->name('review.store')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        if (in_array($role, ['ADMIN', 'DIREKTUR'])) {
            return redirect()->route('executive.dashboard');
        } elseif ($role === 'CLIENT') {
            return app(EXECUTIVEController::class)->index();
        } else {
            return view('OPERATIONALPAGES.PAGE.TEAMTIVE');
        }
    })->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::get('/account', [EXECUTIVEController::class, 'Account'])
            ->name('Account');
    });

    Route::middleware(['auth', 'role:CLIENT'])->group(function () {
        Route::post('/account', [EXECUTIVEController::class, 'storeAccount'])->name('Account.store');
        Route::put('/account/{id}', [EXECUTIVEController::class, 'updateAccount'])->name('Account.update');
        Route::delete('/account/{id}', [EXECUTIVEController::class, 'destroyAccount'])->name('Account.destroy');
    });


    Route::prefix('executive')->name('executive.')->group(function () {
        Route::get('/dashboard', [EXECUTIVEController::class, 'dashboard'])->name('dashboard');
        Route::get('/jadwalkerja', [EXECUTIVEController::class, 'jadwalkerja'])->name('jadwalkerja');
        Route::get('/jadwalpesanan', [EXECUTIVEController::class, 'jadwalpesanan'])->name('jadwalpesanan');
        Route::get('/catalogue', [EXECUTIVEController::class, 'catalogue'])->name('catalogue');
        Route::get('/galleryattire', [EXECUTIVEController::class, 'galleryattire'])->name('galleryattire');
        Route::get('/dataakun', [EXECUTIVEController::class, 'dataakun'])->name('dataakun');
        Route::get('/statistik', [EXECUTIVEController::class, 'statistik'])->name('statistik');
        
        Route::prefix('menupanel')->name('menupanel.')->group(function () {
            Route::get('/homepages/dashboard', [EXECUTIVEController::class, 'menuHomeDashboard'])->name('homepages.dashboard');
            Route::get('/homepages/portofolio', [EXECUTIVEController::class, 'menuHomePortofolio'])->name('homepages.portofolio');
            Route::get('/homepages/pricelist', [EXECUTIVEController::class, 'menuHomePricelist'])->name('homepages.pricelist');
            Route::get('/berkas', [EXECUTIVEController::class, 'menuBerkas'])->name('berkas');
        });

        // AJAX load (tanpa view utama)
        Route::get('/load-content/{page}', [EXECUTIVEController::class, 'loadContent'])->name('load');
        Route::get('/{page}', [EXECUTIVEController::class, 'loadDirect'])->where('page', '[A-Za-z0-9\.\-]+')->name('page');

        Route::prefix('homepages')->name('homepages.')->group(function () {
            Route::get('create/{section}', [EXECUTIVEController::class, 'create'])->name('create');
            Route::post('store/{section}', [EXECUTIVEController::class, 'store'])->name('store');
            Route::post('storePoerto/{section}', [EXECUTIVEController::class, 'storePorto'])->name('storePorto');
            Route::get('edit/{section}/{id}', [EXECUTIVEController::class, 'edit'])->name('edit');
            Route::get('editPorto/{section}/{id}', [EXECUTIVEController::class, 'editPorto'])->name('editPorto');
            Route::put('update/{section}/{id}', [EXECUTIVEController::class, 'update'])->name('update');
            Route::put('updatePorto/{section}/{id}', [EXECUTIVEController::class, 'updatePorto'])->name('updatePorto');
            Route::delete('destroy/{section}/{id}', [EXECUTIVEController::class, 'destroy'])->name('destroy');
        });

        Route::get('/api/slots', [EXECUTIVEController::class, 'apiSlots'])->name('api.slots');
        Route::get('/api/tema-by-name', [EXECUTIVEController::class, 'getTemaByName'])->name('api.temaByName');

        // Submit booking
        Route::post('/booking-client', [EXECUTIVEController::class, 'bookingClientStore'])
            ->name('bookingClient.store');

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
