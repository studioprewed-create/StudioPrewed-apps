<!-- Main Content -->
        <div class="main">
            <!-- Topbar -->
            <div class="topbar">
    <div class="title">
        <h2>STUDIO PREWED</h2>
        <span class="subtitle">Portrait Timeless</span>
    </div>
    <div class="profile">
        <div class="profile-text">
            @if(Auth::check())
                <p class="name">{{ Auth::user()->name }}</p>
                <p class="email">{{ Auth::user()->email }}</p>
                <p class="role">{{ Auth::user()->role }}</p>
            @else
                <p class="name">Guest</p>
                <p class="email">-</p>
            @endif
        </div>
    </div>
</div>

