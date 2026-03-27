<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Studio Prewed | Fotrait Timeless | Pricelist</title>

    {{-- <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/PAGE/Pricelist.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/LAYOUT/Base.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/LAYOUT/Header.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/HOME/Videoplay.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/PRICELIST/Promo.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/PRICELIST/Package.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/PRICELIST/Packagemodal.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/PRICELIST/Temabaju.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/PRICELIST/TemabajuGrid.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/PRICELIST/Booking.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/LAYOUT/Footer.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    @include('HOMEPAGES.SECTION.Header.HeaderPricelist')
    <main>
        @include('HOMEPAGES.SECTION.Videoplay')
        @include('HOMEPAGES.SECTION.Promo')
        @include('HOMEPAGES.SECTION.Package')
        {{-- @include('HOMEPAGES.SECTION.TemaBaju') --}}
        @include('HOMEPAGES.SECTION.TemaBajuGrid')
        {{-- @include('HOMEPAGES.SECTION.Booking') --}}
        @include('HOMEPAGES.SECTION.Footer')
    </main>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        window.__PREFILL__ = @json($prefill ?? ['logged_in' => false]);

        window.__TEMA_DICT__ = @json(
            $temas->groupBy('nama')->map(function($g){
                return $g->map(function($t){ return ['id'=>$t->id,'nama'=>$t->nama,'kode'=>$t->kode]; });
            })
        );

        window.APP_ROUTES = {
            apiSlots: "{{ route('api.slots') }}",
            apiTemaByName: "{{ route('api.temaByName') }}",
        };
    </script>

    <script>
        window.APP_ROUTES = window.APP_ROUTES || {};
        window.APP_ROUTES.apiSlots      = "{{ route('api.slots') }}";
        window.APP_ROUTES.apiTemaByName = "{{ route('api.temaByName') }}";
        window.APP_ROUTES.bookingStore  = "{{ route('bookingClient.store') }}";
    </script>

    {{-- Main JS --}}
    <script src="{{ asset('public/asset/HOMEPAGE/PAGE/Pricelist.js') }}"></script>

    <a href="https://wa.me/628195042022"
        class="wa-float"
        target="_blank"
        aria-label="Chat WhatsApp">
            
        <span class="wa-text">Klik di sini untuk menghubungi kami via WhatsApp</span>
        <i class="fab fa-whatsapp"></i>
    </a>
</body>
</html>
