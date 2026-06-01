<section class="google-review-section" id="googleReviewSection">
    <div class="container">

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

        </div>

        <section class="faq-section" id="faq">
            <div class="faq-wrapper">
                @foreach($faqs as $faq)
                <div class="faq-item">
                    <button class="faq-question" type="button">
                        <div class="faq-left">
                            <span class="faq-number">
                                01
                            </span>
                            <h3>
                                {{ $faq->question }}
                            </h3>
                        </div>
                        <span class="faq-icon">
                            +
                        </span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>
                                {{ $faq->answer }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>
</section>
