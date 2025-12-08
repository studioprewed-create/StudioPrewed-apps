<section class="services-section" id="portrait-services" aria-labelledby="portrait-why-title">
    <div class="container why-wrap">
        <header class="head" data-reveal>
            <span class="eyebrow">Karena Setiap Momen Layak Dirayakan</span>
            <h2 id="portrait-why-title">KENAPA KAMI <span class="hl">?</span>?</h2>
            <p>
                Kami bekerja dengan rapi dan terukur untuk menghadirkan potret yang autentik, tajam, dan natural.
            </p>
        </header>

        <div class="features" role="list">
            @foreach($portraitServices ?? [] as $svc)
                @php
                    $img = $svc->image ? asset('public/storage/'.$svc->image) : asset('asset/IMGhome/default.jpg');
                    $cat = $svc->category ?? 'prewed'; // prewed / family / maternity / ...
                @endphp

                <a href="{{ route('Portofolio', ['category' => $cat]) }}"
                   class="portrait-card-link"
                   role="listitem">
                    <article class="f-card f-card--portrait" tabindex="0">
                        <img src="{{ $img }}"
                             alt="{{ $svc->title }}"
                             class="f-card-image">

                        <div class="f-card-text">
                            <h3>{{ $svc->title }}</h3>
                            @if($svc->description)
                                <p>{{ $svc->description }}</p>
                            @endif
                        </div>

                        <span class="tilt-glare" aria-hidden="true"></span>
                    </article>
                </a>
            @endforeach
        </div>
    </div>
</section>
