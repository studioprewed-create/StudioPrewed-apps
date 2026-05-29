<div class="sub-sidebar">

    <ul class="sub-menu">

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.dataakun') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.dataakun') }}"
               data-subpage="ManagementContent.DataAkun">
                <i class="fas fa-users"></i>
                <span>Data Akun</span>
            </a>
        </li>

         <li class="sub-menu-item {{ request()->routeIs('executive.subpage.dataPartnership') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.dataPartnership') }}"
               data-subpage="PartnershipContent.DataPartnership">
                <i class="fas fa-handshake"></i>
                <span>Data Partnership</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.kategoriPartnership') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.kategoriPartnership') }}"
               data-subpage="PartnershipContent.KategoriPartnership">
                <i class="fas fa-tags"></i>
                <span>Kategori</span>
            </a>
        </li>

    </ul>

</div>