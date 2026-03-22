<section class="services-section" id="portrait-services" aria-labelledby="portrait-why-title">
    <div class="container why-wrap">
        <header class="head" data-reveal>
            <span class="eyebrow">Karena Setiap Momen Layak Dirayakan</span>
            <h2 id="portrait-why-title">KENAPA <span class="hl">KAMI</span>?</h2>
            <p>
                Kami bekerja dengan rapi dan terukur untuk menghadirkan potret yang autentik, tajam, dan natural.
            </p>
        </header>

        <div class="services-grid" role="list">
            @foreach($portraitServices ?? [] as $svc)
                @php
                    $img = $svc->image ? asset('public/storage/'.$svc->image) : asset('asset/IMGhome/default.jpg');
                    $cat = $svc->category ?? 'prewed';
                @endphp

                <a href="{{ route('Portofolio', ['category' => $cat]) }}" class="service-card">
                    
                    <div class="service-image">
                        <img src="{{ $img }}" alt="{{ $svc->title }}">
                        <div class="service-overlay"></div>
                    </div>

                    <div class="service-content">
                        <h3>{{ $svc->title }}</h3>
                        @if($svc->description)
                            <p>{{ $svc->description }}</p>
                        @endif

                        <span class="service-link">
                            Lihat Portfolio →
                        </span>
                    </div>

                </a>
            @endforeach

        </div>
    </div>
</section>
