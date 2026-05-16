<section class="google-review-section" id="googleReviewSection">
<div class="container google-review-container">

    @php
        $average = number_format($googleReviews->avg('rating'), 1);
        $total = $googleReviews->count();
    @endphp

    {{-- =========================================================
         HEADING
    ========================================================= --}}

    <div class="google-review-heading">

        <div>

            <span class="eyebrow">
                GOOGLE REVIEW
            </span>

            <h2>
                APA KATA
                <span>MEREKA</span>
            </h2>

            <p>
                Pengalaman nyata dari client yang telah menggunakan layanan kami.
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

    {{-- =========================================================
         FILTER
    ========================================================= --}}

    <div class="google-review-filter" id="googleReviewFilter">

        {{-- SEMUA --}}
        <a
            href="#"
            class="active"
            data-filter="all">

            Semua

        </a>

        {{-- DROPDOWN BINTANG --}}
        <div class="google-filter-dropdown">

            <button
                class="google-filter-dropdown-btn"
                id="googleStarDropdownBtn">

                ⭐ Rating

                <i class="fa-solid fa-chevron-down"></i>

            </button>

            <div
                class="google-filter-dropdown-menu"
                id="googleStarDropdownMenu">

                <a href="#" data-filter="5">
                    ⭐ 5 Bintang
                </a>

                <a href="#" data-filter="4">
                    ⭐ 4 Bintang
                </a>

                <a href="#" data-filter="3">
                    ⭐ 3 Bintang
                </a>

                <a href="#" data-filter="2">
                    ⭐ 2 Bintang
                </a>

                <a href="#" data-filter="1">
                    ⭐ 1 Bintang
                </a>

            </div>

        </div>

        {{-- FOTO --}}
        <a href="#" data-filter="photo">

            📷 Dengan Foto

        </a>

        {{-- TERBARU --}}
        <a href="#" data-filter="newest">

            🕒 Terbaru

        </a>

    </div>

    {{-- =========================================================
         GRID
    ========================================================= --}}

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
                data-photo="{{ count($images) ? 1 : 0 }}"
                data-date="{{ $review->review_date }}"
            >

                {{-- =========================================================
                     TOP
                ========================================================= --}}

                <div class="google-review-top">

                    <div class="google-review-user">

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

                        <div>

                            <h4>
                                {{ $review->author_name }}
                            </h4>

                            <span>
                                {{ \Carbon\Carbon::parse($review->review_date)->translatedFormat('d F Y') }}
                            </span>

                        </div>

                    </div>

                    <div class="google-review-icon">
                        <i class="fa-brands fa-google"></i>
                    </div>

                </div>

                {{-- =========================================================
                     STARS
                ========================================================= --}}

                <div class="google-review-stars">

                    @for($i=1;$i<=5;$i++)

                        @if($i <= $review->rating)
                            <i class="fa-solid fa-star"></i>
                        @else
                            <i class="fa-regular fa-star"></i>
                        @endif

                    @endfor

                </div>

                {{-- =========================================================
                     TEXT
                ========================================================= --}}

                <div class="google-review-text">

                    {{ $review->review_text }}

                </div>

                {{-- =========================================================
                     BUTTON MORE
                ========================================================= --}}

                <button
                    class="google-review-more"

                    data-author="{{ $review->author_name }}"
                    data-rating="{{ $review->rating }}"
                    data-date="{{ \Carbon\Carbon::parse($review->review_date)->translatedFormat('d F Y') }}"
                    data-text="{{ $review->review_text }}"
                >
                    Read More
                </button>

                {{-- =========================================================
                     IMAGES
                ========================================================= --}}

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

    {{-- =========================================================
         PAGINATION
    ========================================================= --}}

    <div
        class="google-review-pagination"
        id="googleReviewPagination">
    </div>

</div>

</section>

{{-- =========================================================
MODAL
========================================================= --}}

<div class="google-review-modal" id="googleReviewModal">

```
<div class="google-review-modal-backdrop"></div>

<div class="google-review-modal-content">

    <button
        class="google-review-modal-close"
        id="googleReviewClose">

        <i class="fa-solid fa-xmark"></i>

    </button>

    <div class="google-review-modal-user">

        <div
            class="google-review-modal-avatar"
            id="modalAvatar">
            A
        </div>

        <div>

            <h3 id="modalAuthor"></h3>

            <div
                class="google-review-stars"
                id="modalStars">
            </div>

            <span id="modalDate"></span>

        </div>

    </div>

    <div
        class="google-review-modal-text"
        id="modalText">
    </div>

</div>

</div>
