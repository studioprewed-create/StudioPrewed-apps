<section class="google-review-section" id="googleReviewSection">

    <div class="container">

        <div class="head" data-reveal>
            <span class="eyebrow">
                GOOGLE REVIEW
            </span>

            <h2>
                APA KATA
                <span class="hl">MEREKA</span>
            </h2>

            <p>
                Pengalaman nyata dari client yang telah menggunakan layanan kami.
            </p>
        </div>

        @php
            $average = number_format($googleReviews->avg('rating'), 1);
            $total   = $googleReviews->count();
        @endphp

        {{-- SUMMARY --}}
        <div class="google-summary-box">

            <div class="google-summary-left">

                <div class="google-rating-value">
                    {{ $average }}
                </div>

                <div class="google-stars">
                    @for($i=1; $i<=5; $i++)
                        <i class="fa-solid fa-star"></i>
                    @endfor
                </div>

                <div class="google-total-review">
                    {{ $total }} Reviews
                </div>

            </div>

            <div class="google-summary-right">

                @for($star = 5; $star >=1; $star--)

                    @php
                        $count = $googleReviews->where('rating', $star)->count();
                        $percent = $total ? ($count / $total) * 100 : 0;
                    @endphp

                    <div class="google-progress-item">

                        <span>{{ $star }}</span>

                        <div class="google-progress-bar">
                            <div
                                class="google-progress-fill"
                                style="width:{{ $percent }}%">
                            </div>
                        </div>

                        <small>{{ $count }}</small>

                    </div>

                @endfor

            </div>

        </div>

        {{-- FILTER --}}
        <div class="google-review-filter">

            <div class="google-review-count">
                Semua Review
            </div>

            <select id="googleReviewSort">

                <option value="newest">
                    Terbaru
                </option>

                <option value="highest">
                    Rating Tertinggi
                </option>

                <option value="lowest">
                    Rating Terendah
                </option>

                <option value="photo">
                    Dengan Foto
                </option>

            </select>

        </div>

        {{-- LIST --}}
        <div class="google-review-grid" id="googleReviewGrid">

            @foreach($googleReviews as $review)

                @php
                    $images = [];

                    if(!empty($review->review_images)){
                        $images = json_decode($review->review_images, true) ?? [];
                    }
                @endphp

                <div
                    class="google-review-card"
                    data-rating="{{ $review->rating }}"
                    data-date="{{ $review->review_date }}"
                    data-photo="{{ count($images) > 0 ? 1 : 0 }}"
                >

                    <div class="google-review-top">

                        <div class="google-review-profile">

                            @if($review->profile_photo)

                                <img
                                    src="{{ asset($review->profile_photo) }}"
                                    alt="{{ $review->author_name }}"
                                >

                            @else

                                <div class="google-review-avatar">
                                    {{ strtoupper(substr($review->author_name,0,1)) }}
                                </div>

                            @endif

                        </div>

                        <div class="google-review-user">

                            <h4>
                                {{ $review->author_name }}
                            </h4>

                            <div class="google-review-stars">

                                @for($i=1;$i<=5;$i++)

                                    @if($i <= $review->rating)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif

                                @endfor

                            </div>

                            <span>
                                {{ \Carbon\Carbon::parse($review->review_date)->translatedFormat('d F Y') }}
                            </span>

                        </div>

                    </div>

                    <div class="google-review-content">

                        {{ $review->review_text }}

                    </div>

                    @if(count($images))

                        <div class="google-review-images">

                            @foreach($images as $img)

                                <img
                                    src="{{ asset($img) }}"
                                    alt="review image"
                                >

                            @endforeach

                        </div>

                    @endif

                </div>

            @endforeach

        </div>

        {{-- PAGINATION --}}
        <div
            class="google-review-pagination"
            id="googleReviewPagination">
        </div>

    </div>

</section>