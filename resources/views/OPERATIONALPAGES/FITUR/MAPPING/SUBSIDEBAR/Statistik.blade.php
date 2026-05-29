<div class="sub-sidebar">

    <ul class="sub-menu">

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.statistiksurvey') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.statistiksurvey') }}"
               data-subpage="SUBCONTENT.StatistikSurvey">
                <i class="fas fa-chart-line"></i>
                <span>Survey</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.statistikreview') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.statistikreview') }}"
               data-subpage="SUBCONTENT.StatistikReview">
                <i class="fas fa-comments"></i>
                <span>Review</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.statistikpengeluaran') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.statistikpengeluaran') }}"
               data-subpage="SUBCONTENT.StatistikPengeluaran">
                <i class="fas fa-wallet"></i>
                <span>Pengeluaran</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.statistikpendapatan') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.statistikpendapatan') }}"
               data-subpage="SUBCONTENT.StatistikPendapatan">
                <i class="fas fa-coins"></i>
                <span>Pendapatan</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.statistikkinerja') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.statistikkinerja') }}"
               data-subpage="SUBCONTENT.StatistikKinerja">
                <i class="fas fa-user-check"></i>
                <span>Kinerja</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.statistikkatalog') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.statistikkatalog') }}"
               data-subpage="SUBCONTENT.StatistikKatalog">
                <i class="fas fa-images"></i>
                <span>Katalog</span>
            </a>
        </li>

    </ul>

</div>