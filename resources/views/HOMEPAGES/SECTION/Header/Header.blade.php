<header id="siteHeader">
    <div class="brand-wrapper">
        <img src="{{asset('public/asset/PICTURESET/LOGOSPDASHBOARD.png') }}" alt="Logo" class="logo-img">
        <span class="brand">Studio Prewed</span>
    </div>
    <nav aria-label="Main navigation">
        <a href="#home">Home</a>
        <a href="{{ route('Portofolio') }}">Portofolio</a>
        <a href="{{ route('Pricelist') . '#bookingWizard' }}">Booking</a>
        <a href="{{ route('Pricelist') . '#promoCarousel'}}">Pricelist</a>
        @auth
            <a href="{{ route('Account') }}">Account</a>
            <a href="{{ route('logout') }}">Logout</a>
        @endauth

        @guest
            <a href="{{ route('login') }}">Login</a>
        @endguest
    </nav>
</header>