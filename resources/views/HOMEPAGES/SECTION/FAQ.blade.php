<section class="google-review-section" id="googleReviewSection">

    <div class="google-review-heading">

        <div>

            <span class="eyebrow">
                SEMUA PERTANYAAN DARI CLIENT
            </span>

            <h2>
                FAQ
                <span>--TANYA & JAWAB--</span>
            </h2>

            <p>
               Dari cara booking hingga waktu pengerjaan foto, semua jawaban yang Anda butuhkan tersedia di sini. Kami menyusunnya sesederhana mungkin agar mudah dipahami.
            </p>

        </div>

        <div class="google-review-score">

            <div class="score-number">
                {{ $average }}
            </div>

            <div>

                <div class="score-stars">
                    ★★★★★
                </div>

                <div class="score-text">
                    {{ $total }} Reviews
                </div>

            </div>

        </div>

    </div>

    <section class="faq-section" id="faq">
        <div class="container">
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
</section>
