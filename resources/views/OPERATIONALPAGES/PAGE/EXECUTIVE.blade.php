<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Studio Prewed - Executive')</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/OPERATIONALPAGE/EXECUTIVE/PAGE/EXECUTIVE.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    @php
        $page = $page ?? 'Dashboard';
    @endphp

    @include('OPERATIONALPAGES.FITUR.MAPPING.sidebar')
    @include('OPERATIONALPAGES.FITUR.MAPPING.topbar')

    <div class="container">
       <div id="main-content" class="content" data-current-page="{{ $page }}">
            @if(View::exists("OPERATIONALPAGES.FITUR.MAINCONTENT.$page"))
                @include("OPERATIONALPAGES.FITUR.MAINCONTENT.$page")
            @else
                <div class="alert alert-warning" style="padding:20px; background:#2b2b2b; color:#f6ad55; border-radius:8px;">
                    <b>Halaman "{{ $page }}" belum dibuat.</b>
                </div>
            @endif
        </div>
    </div>
    <script src="{{ asset('asset/OPERATIONALPAGE/EXECUTIVE/PAGE/EXECUTIVE.js') }}"></script>
</body>
</html>
