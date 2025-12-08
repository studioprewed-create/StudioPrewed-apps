<section class="services-section" id="services" aria-labelledby="why-title">
    <div class="container why-wrap">
        <header class="head" data-reveal>
            <span class="eyebrow">Semua yang biasanya ditanyakan klien sebelum memotret</span>
            <h2 id="why-title">FAQ--<span class="hl">--TANYA & JAWAB</span></h2>
            <p>Dari cara booking hingga waktu pengerjaan foto, semua jawaban yang Anda butuhkan tersedia di sini. Kami menyusunnya sesederhana mungkin agar mudah dipahami.</p>
        </header>
    </div>
</section>

<section class="faq-section" id="faq">
    <div class="faq-container">
        <div class="faq-list">
            @foreach($faqs as $faq)
                <div class="faq-item">
                    <button class="faq-question" type="button">
                        <h3>{{ $faq->question }}</h3>
                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="faq-answer">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
