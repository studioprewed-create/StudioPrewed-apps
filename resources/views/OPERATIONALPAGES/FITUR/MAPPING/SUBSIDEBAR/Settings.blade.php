<div class="sub-sidebar">

    <ul class="sub-menu">

        <li class="sub-menu-item {{ request()->routeIs('') ? 'active' : '' }}">
            <a href="{{ route('') }}">
                <span>Profile</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('') ? 'active' : '' }}">
            <a href="{{ route('') }}">
                <span>Appearance</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('') ? 'active' : '' }}">
            <a href="{{ route('') }}">
                <span>Notification</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('') ? 'active' : '' }}">
            <a href="{{ route('') }}">
                <span>Activity</span>
            </a>
        </li>

    </ul>

</div>
