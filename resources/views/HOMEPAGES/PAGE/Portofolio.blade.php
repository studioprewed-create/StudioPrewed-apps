<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Studio Prewed | Potrait Timeless | portofolio</title>
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/LAYOUT/Base.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/LAYOUT/Header.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/HOME/Videoplay.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/PORTOFOLIO/Galleryfictures.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/LAYOUT/Footer.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    </style>
</head>

<body>
    @include('HOMEPAGES.SECTION.Header.HeaderPorto')
    <main>
        @include('HOMEPAGES.SECTION.Videoplay')
        @include('HOMEPAGES.SECTION.GalleryFictures')
        @include('HOMEPAGES.SECTION.Footer')
    </main>
    <script type="module" src="{{ asset('public/asset/HOMEPAGE/JSHOME/app.js') }}"></script>
    <script src="{{ asset('public/asset/HOMEPAGE/JSHOME/Theme.js') }}"></script>

    <a href="#Portofolio" class="section-float" data-down-target="#Portofolio" data-down-text="Explore Portofolio"
        data-up-target="#home" data-up-text="Back To Top" aria-label="Scroll Navigation">

        <span class="section-text">
            Explore Portofolio
        </span>

        <i class="fas fa-arrow-down"></i>

    </a>

    {{-- <button id="themeToggle"
        class="theme-toggle"
        aria-label="Toggle Theme">

        <i class="fas fa-moon"></i>

    </button> --}}

    <div class="wa-wrapper">
        <button type="button" class="wa-float" id="waFloatButton" aria-label="Pilih kontak WhatsApp"
            aria-controls="waContactMenu" aria-expanded="false">

            <span class="wa-text">Hubungi Kami</span>
            <i class="fab fa-whatsapp"></i>
        </button>

        <div class="wa-menu" id="waContactMenu">

            <a href="https://wa.me/6285295251525" class="wa-option" target="_blank" rel="noopener noreferrer">

                <span class="wa-option-content">
                    <strong>Admin Studio Utama</strong>
                    <small>Informasi dan konsultasi</small>
                </span>

                <i class="fab fa-whatsapp"></i>
            </a>

            <a href="https://wa.me/628195042022" class="wa-option" target="_blank" rel="noopener noreferrer">

                <span class="wa-option-content">
                    <strong>Admin Studio</strong>
                    <small>Informasi dan konsultasi</small>
                </span>

                <i class="fab fa-whatsapp"></i>
            </a>

        </div>
    </div>
</body>

</html>
