<div class="sub-sidebar">

    <ul class="sub-menu">

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.dataakun') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.dataakun') }}"
               data-subpage="Management.DataAkun">
                <i class="fas fa-users"></i>
                <span>Data Akun</span>
            </a>
        </li>

         <li class="sub-menu-item {{ request()->routeIs('executive.subpage.dataPartnership') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.dataPartnership') }}"
               data-subpage="Management.Partnership">
                <i class="fas fa-handshake"></i>
                <span>Data Partnership</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.kategoriPartnership') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.kategoriPartnership') }}"
               data-subpage="Management.KPartnership">
                <i class="fas fa-tags"></i>
                <span>Kategori</span>
            </a>
        </li>

    </ul>

</div>