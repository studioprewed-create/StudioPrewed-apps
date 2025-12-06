<section class="services-section" id="about" aria-labelledby="portrait-title">
    <div class="container about-wrap">
        <header class="head" data-reveal>
            <span class="eyebrow">REVIEW</span>
            <h2 id="portrait-title">WE ARE <span class="hl">PORTRAIT</span> ARTISTS</h2>
            <p>Kami menghadirkan keindahan setiap individu melalui pencahayaan dramatis, komposisi artistic, dan sentuhan editing yang timeless.</p>
        </header>
    </div>
</section>

    <button class="btn-modal" id="openReviewModal" type="button" aria-haspopup="dialog" aria-controls="reviewModal">
        <svg class="btn-modal__icon" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.62L12 2 9.19 8.62 2 9.24l5.46 4.73L5.82 21z" fill="currentColor"/>
        </svg>
        <span>Tambah Review</span>
    </button>

    @include('HOMEPAGES.MODAL.ReviewModal')

    <section id="testi">
        <div class="testi-container">
            <div class="testi-left">
            <div class="testi-wrap">
                <div class="slider" id="tSlider">
                <div class="slides">
                    @foreach($reviews as $r)
                    <div class="t-item" id="testi-{{ $loop->iteration }}">
                        <div class="t-top">
                        <div class="avatar">
                            <img
                            src="{{ !empty($r->image) 
                                    ? asset('public/storage/'.$r->image) 
                                    : (!empty($r->avatar) 
                                        ? asset('storage/'.$r->avatar) 
                                        : 'https://via.placeholder.com/100') }}"
                            alt="Foto {{ $r->name }}"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='https://via.placeholder.com/100'">
                        </div>
                        <div>
                            <div class="t-name">{{ $r->name }}</div>
                        </div>
                        </div>

                        @if(isset($r->rating))
                        <div class="t-stars" aria-label="Rating {{ (int)$r->rating }} dari 5">
                            @for($i=1; $i<=5; $i++)
                            <i class="{{ $i <= (int)$r->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                            @endfor
                        </div>
                        @endif

                        @if(!empty($r->content))
                        <p class="t-quote">{{ $r->content }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="t-nav">
                    <button class="t-btn" data-dir="-1" aria-label="Prev"><i class="fa fa-chevron-left"></i></button>
                    <button class="t-btn" data-dir="1" aria-label="Next"><i class="fa fa-chevron-right"></i></button>
                </div>
                </div>
            </div>
            </div>

            <div class="testi-right">
                <div class="testi-media">
                    @if(isset($heroes) && $heroes->isNotEmpty())
                    <div class="testi-media-grid">
                        @foreach($heroes as $hero)
                        <img
                            src="{{ asset('public/storage/'.$hero->image) }}"
                            alt="Studio Prewed Team"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='{{ asset('asset/IMGhome/bg1.jpg') }}'">
                        @endforeach
                    </div>
                    @else
                    <img
                        src="{{ asset('asset/IMGhome/bg1.jpg') }}"
                        alt="Studio Prewed Team"
                        loading="lazy">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="reviews-cta" style="display:flex; gap:12px; justify-content:center; margin:24px 0 8px;">
        <button id="btnOpenReviews"  type="button" class="btn-reviews" aria-controls="reviewsSection" aria-expanded="false">Selengkapnya</button>
        <button id="btnHideReviews"  type="button" class="btn-reviews is-ghost" aria-controls="reviewsSection" hidden>Sembunyikan</button>
    </div>