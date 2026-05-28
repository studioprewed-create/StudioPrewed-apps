<div class="sub-sidebar">

    <ul class="sub-menu">

        <li class="sub-menu-item {{ request()->routeIs('executive.settings.profile') ? 'active' : '' }}">
            <a href="{{ route('executive.settings.profile') }}">
                <span>Profile</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.settings.appearance') ? 'active' : '' }}">
            <a href="{{ route('executive.settings.appearance') }}">
                <span>Appearance</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.settings.notification') ? 'active' : '' }}">
            <a href="{{ route('executive.settings.notification') }}">
                <span>Notification</span>
            </a>
        </li>

        <li class="sub-menu-item {{ request()->routeIs('executive.settings.activity') ? 'active' : '' }}">
            <a href="{{ route('executive.settings.activity') }}">
                <span>Activity</span>
            </a>
        </li>

    </ul>

</div>
