@if(isset($promos) && $promos->count())
<section class="promo-section">
    <div class="promo-container" id="promoCarousel" data-interval="4000">
    @foreach($promos as $i => $p)
        <div class="promo-slide {{ $i === 0 ? 'is-active' : '' }}">
        <img
            src="{{ asset('public/storage/'.$p->image) }}"
            alt="Promo {{ $i+1 }}"
            class="promo-img"
            loading="lazy">
        <div class="promo-overlay"></div>
        </div>
    @endforeach
    <div class="promo-dots">
        @foreach($promos as $i => $p)
        <button class="promo-dot {{ $i === 0 ? 'is-active' : '' }}" data-index="{{ $i }}" aria-label="slide {{ $i+1 }}"></button>
        @endforeach
    </div>
    </div>
</section>
@endif