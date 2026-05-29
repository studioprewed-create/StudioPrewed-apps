public function subStore(Request $request, $section)
{
    $redirectMap = [

        // Management
        'user' => [
            'page'    => 'Management',
            'subpage' => 'DataAkun',
        ],

        // Brand
        'brand-category' => [
            'page'    => 'Brand',
            'subpage' => 'KategoriPartnership',
        ],

        // Jadwal
        'bookingexecutive' => [
            'page'    => 'Schedule',
            'subpage' => 'JadwalPesanan',
        ],

    ];

    $redirect = $redirectMap[$section] ?? [
        'page'    => 'Dashboard',
        'subpage' => null,
    ];



    /*
    |--------------------------------------------------------------------------
    | Management
    |--------------------------------------------------------------------------
    */

    elseif ($section === 'user') {

        //
    }



    /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    */

    elseif ($section === 'brand-category') {

        //
    }



    /*
    |--------------------------------------------------------------------------
    | Schedule
    |--------------------------------------------------------------------------
    */

    elseif ($section === 'bookingexecutive') {

        //
    }



    return redirect()->route(
        'executive.subpage',
        [
            'page'    => $redirect['page'],
            'subpage' => $redirect['subpage'],
        ]
    )->with(
        'success',
        ucfirst($section) . ' berhasil ditambahkan!'
    );
}

public function subUpdate(Request $request, $section, $id)
{
    $redirectMap = [

        'user' => [
            'page'    => 'Management',
            'subpage' => 'DataAkun',
        ],

        'brand-category' => [
            'page'    => 'Brand',
            'subpage' => 'KategoriPartnership',
        ],

        'bookingexecutive' => [
            'page'    => 'Schedule',
            'subpage' => 'JadwalPesanan',
        ],

    ];

    $redirect = $redirectMap[$section] ?? [
        'page'    => 'Dashboard',
        'subpage' => null,
    ];



    elseif ($section === 'user') {

        //
    }

    elseif ($section === 'brand-category') {

        //
    }

    elseif ($section === 'bookingexecutive') {

        //
    }



    return redirect()->route(
        'executive.subpage',
        [
            'page'    => $redirect['page'],
            'subpage' => $redirect['subpage'],
        ]
    )->with(
        'success',
        ucfirst($section) . ' berhasil diperbarui!'
    );
}

public function subDestroy(Request $request, $section, $id)
{
    $redirectMap = [

        'user' => [
            'page'    => 'Management',
            'subpage' => 'DataAkun',
        ],

        'brand-category' => [
            'page'    => 'Brand',
            'subpage' => 'KategoriPartnership',
        ],

        'bookingexecutive' => [
            'page'    => 'Schedule',
            'subpage' => 'JadwalPesanan',
        ],

    ];

    $redirect = $redirectMap[$section] ?? [
        'page'    => 'Dashboard',
        'subpage' => null,
    ];



    elseif ($section === 'user') {

        //
    }

    elseif ($section === 'brand-category') {

        //
    }

    elseif ($section === 'bookingexecutive') {

        //
    }



    return redirect()->route(
        'executive.subpage',
        [
            'page'    => $redirect['page'],
            'subpage' => $redirect['subpage'],
        ]
    )->with(
        'success',
        ucfirst($section) . ' berhasil dihapus!'
    );
}

