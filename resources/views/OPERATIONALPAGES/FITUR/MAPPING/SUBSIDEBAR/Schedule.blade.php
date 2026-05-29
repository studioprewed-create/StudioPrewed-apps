<div class="sub-sidebar">

    <ul class="sub-menu">

        <li class="menu-item {{ request()->routeIs('executive.subpage.jadwalpesanan') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.jadwalpesanan') }}"
               data-subpage="Schedule.JadwalPesanan">
                <i class="fas fa-calendar-check"></i>
                <span>Jadwal Pesanan</span>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('executive.subpage.jadwalkerja') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.jadwalkerja') }}"
               data-subpage="Schedule.Jadwalkerja">
                <i class="fas fa-users"></i>
                <span>Jadwal Kerja</span>
            </a>
        </li>

    </ul>

</div>