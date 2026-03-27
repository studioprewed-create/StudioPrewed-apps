<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio Prewed | Fotrait Timeless | Home</title>
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/LAYOUT/Base.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/LAYOUT/Header.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/HOME/Videoplay.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/HOME/Marquee.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/HOME/About.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/HOME/Whyus.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/HOME/Review.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/HOME/Faq.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/LAYOUT/Footer.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;900&display=swap">
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
        @include('HOMEPAGES.SECTION.FAQ')
        @include('HOMEPAGES.SECTION.Footer')
    </main>
 <script src="{{ asset('public/asset/HOMEPAGE/PAGE/Dashboard.js') }}"></script>

    <a href="https://wa.me/628195042022"
        class="wa-float"
        target="_blank"
        aria-label="Chat WhatsApp">
        
        <i class="fab fa-whatsapp"></i>
        <span class="wa-text">Klik di sini untuk menghubungi kami via WhatsApp</span>
    </a>

</body>
</html>
