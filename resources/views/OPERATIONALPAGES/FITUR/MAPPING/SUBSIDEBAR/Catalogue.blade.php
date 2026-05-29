<div class="sub-sidebar">

    <ul class="sub-menu">

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.package') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.package') }}"
               data-subpage="Catalogue.Package">
                <i class="fas fa-users"></i>
                <span>Package</span>
            </a>
        </li>

         <li class="sub-menu-item {{ request()->routeIs('executive.subpage.temaBaju') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.temaBaju') }}"
               data-subpage="Catalogue.TemaBaju">
                <i class="fas fa-handshake"></i>
                <span>Tema Baju</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.kategoritemabaju') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.kategoritemabaju') }}"
               data-subpage="Catalogue.KategoriBaju">
                <i class="fas fa-tags"></i>
                <span>Kategori Baju</span>
            </a>
        </li>

    </ul>

</div>