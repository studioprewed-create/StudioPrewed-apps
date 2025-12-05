<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Studio Prewed | Fotografi Klasik Modern</title>

    {{-- Main stylesheet --}}
    <link rel="stylesheet" href="{{ asset('asset/HOMEPAGE/PAGE/Pricelist.css') }}">

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    @include('HOMEPAGES.SECTION.Header.HeaderPricelist')
    <main>
        @include('HOMEPAGES.SECTION.Videoplay')
        @include('HOMEPAGES.SECTION.Promo')
        @include('HOMEPAGES.SECTION.Package')
        @include('HOMEPAGES.SECTION.TemaBaju')
        @include('HOMEPAGES.SECTION.Booking')
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
            apiSlots: "{{ route('executive.api.slots') }}",
            apiTemaByName: "{{ route('executive.api.temaByName') }}",
        };
    </script>

    <script>
        window.APP_ROUTES = window.APP_ROUTES || {};
        window.APP_ROUTES.apiSlots      = "{{ route('executive.api.slots') }}";
        window.APP_ROUTES.apiTemaByName = "{{ route('executive.api.temaByName') }}";
        window.APP_ROUTES.bookingStore  = "{{ route('executive.bookingClient.store') }}";
    </script>

    {{-- Main JS --}}
    <script src="{{ asset('asset/HOMEPAGE/PAGE/Pricelist.js') }}"></script>
</body>
</html>
