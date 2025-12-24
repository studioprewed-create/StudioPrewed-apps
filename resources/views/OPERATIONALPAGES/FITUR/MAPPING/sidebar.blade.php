<div class="sidebar">
    <div class="logo">
        <a href="{{ route('executive.dashboard') }}">
            <img src="{{ asset('public/asset/PICTURESET/LOGOSPREGISTRASI.png') }}" alt="Logo Studio Prewed">
        </a>
    </div>

    <ul class="menu">
        @if(in_array(auth()->user()->role, ['ADMIN', 'DIREKTUR']))
            <li class="menu-item {{ request()->routeIs('executive.dashboard') ? 'active' : '' }}">
                <a href="{{ route('executive.dashboard') }}" data-page="Dashboard">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('executive.jadwalkerja') ? 'active' : '' }}">
                <a href="{{ route('executive.jadwalkerja') }}" data-page="JadwalKerja">
                    <i class="fas fa-users"></i> <span>Jadwal Kerja</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('executive.jadwalpesanan') ? 'active' : '' }}">
                <a href="{{ url('/executive/JadwalPesanan') }}" data-page="JadwalPesanan">
                    <i class="fas fa-calendar-check"></i> <span>Jadwal Pesanan</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('executive.catalogue') ? 'active' : '' }}">
                <a href="{{ route('executive.catalogue') }}" data-page="Catalogue">
                    <i class="fa-solid fa-images"></i> <span>Catalogue</span>
                </a>
            </li>
            <li class="menu-item dropdown">
                <a href="#" class="dropdown-toggle">
                    <i class="fa-solid fa-folder"></i> <span>Menu Panel</span>
                    <i class="fa-solid fa-chevron-right submenu-icon"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('executive.menupanel.homepages.dashboard') }}" data-page="MenuPanel.HomePages.Dashboard">
                            Home Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('executive.menupanel.homepages.portofolio') }}" data-page="MenuPanel.HomePages.Portofolio">
                            Portofolio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('executive.menupanel.homepages.pricelist') }}" data-page="MenuPanel.HomePages.Pricelist">
                            Pricelist
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item {{ request()->routeIs('executive.catalogue') ? 'active' : '' }}">
                <a href="{{ route('executive.menupanel.berkas') }}" data-page="MenuPanel.Berkas">
                    <i class="fa-solid fa-images"></i> <span>Berkas</span>
                </a>
            </li>
            @if(auth()->user()->role === 'DIREKTUR')
                <li class="menu-item {{ request()->routeIs('executive.dataakun') ? 'active' : '' }}">
                    <a href="{{ route('executive.dataakun') }}" data-page="DataAkun">
                        <i class="fa-solid fa-id-card"></i> <span>Data Akun</span>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('executive.statistik') ? 'active' : '' }}">
                    <a href="{{ route('executive.statistik') }}" data-page="Statistik">
                        <i class="fa-solid fa-chart-line"></i> <span>Statistik</span>
                    </a>
                </li>
            @endif
        @endif


        @if(auth()->user()->role === 'ATTIRE')
            <li class="menu-item">
                <a href="{{ route('executive.dashboard') }}" data-page="Dashboard">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('executive.jadwalkerja') }}" data-page="JadwalKerja">
                    <i class="fas fa-users"></i> <span>Jadwal Kerja</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ url('/executive/JadwalPesanan') }}" data-page="JadwalPesanan">
                    <i class="fas fa-calendar-check"></i> <span>Jadwal Pesanan</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('executive.galleryattire') }}" data-page="GalleryAttire">
                    <i class="fa-solid fa-shirt"></i> <span>Gallery Attire</span>
                </a>
            </li>
        @endif

        @if(in_array(auth()->user()->role, ['EDITOR','PHOTOGRAFER','VIDEOGRAFER','MAKE_UP','ADMIN_EDITOR']))
            <li class="menu-item">
                <a href="{{ route('executive.dashboard') }}" data-page="Dashboard">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('executive.jadwalkerja') }}" data-page="JadwalKerja">
                    <i class="fas fa-users"></i> <span>Jadwal Kerja</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ url('/executive/JadwalPesanan') }}" data-page="JadwalPesanan">
                    <i class="fas fa-calendar-check"></i> <span>Jadwal Pesanan</span>
                </a>
            </li>
        @endif

        <li class="menu-item">
            <a href="{{ route('logout') }}" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>
