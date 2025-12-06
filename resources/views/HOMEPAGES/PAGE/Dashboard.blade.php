<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Studio Prewed | Fotografi Klasik Modern</title>
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/PAGE/Dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
  </style>
</head>
<body>
    @include('HOMEPAGES.SECTION.Header.Header')
    <main>
        @include('HOMEPAGES.SECTION.Videoplay')
        @include('HOMEPAGES.SECTION.Marquee')
        @include('HOMEPAGES.SECTION.Aboutus')
        @include('HOMEPAGES.SECTION.WhyUs')
        @include('HOMEPAGES.SECTION.TreeProses')
        @include('HOMEPAGES.SECTION.Review')
        @include('HOMEPAGES.SECTION.FAQ')
        @include('HOMEPAGES.SECTION.Footer')
    </main>
 <script src="{{ asset('public/asset/HOMEPAGE/PAGE/Dashboard.js') }}"></script>

</body>
</html>
