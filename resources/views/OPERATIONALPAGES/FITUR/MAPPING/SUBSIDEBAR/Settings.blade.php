<div class="sub-sidebar">

    <ul class="sub-menu">

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.profile') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.profile') }}"
               data-subpage="SUBCONTENT.Profile">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.appearance') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.appearance') }}"
               data-subpage="SUBCONTENT.Appearance">
                <i class="fas fa-palette"></i>
                <span>Appearance</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.notification') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.notification') }}"
               data-subpage="SUBCONTENT.Notification">
                <i class="fas fa-bell"></i>
                <span>Notification</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.subpage.activity') ? 'active' : '' }}">
            <a href="{{ route('executive.subpage.activity') }}"
               data-subpage="SUBCONTENT.Activity">
                <i class="fas fa-clock-rotate-left"></i>
                <span>Activity</span>
            </a>
        </li>

    </ul>

</div>