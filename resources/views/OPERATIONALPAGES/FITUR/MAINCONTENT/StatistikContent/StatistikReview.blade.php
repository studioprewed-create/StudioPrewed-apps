@php
use Illuminate\Support\Str;
use Carbon\Carbon;
@endphp

<section class="google-review-section">

    <div class="google-review-container">

        <div class="google-review-heading">

            <div>

                <h2>Google Reviews</h2>

                <p>
                    Review asli dari pelanggan kami
                </p>

            </div>

            <form
                action="{{ route('executive.homepages.store', 'googlereview') }}"
                method="POST">

                @csrf

                <button
                    type="submit"
                    class="filter-btn"
                >

                    Refresh Google Review

                </button>

            </form>

            <div class="google-review-score">

                <div class="score-number">
                    {{ number_format($googleReviews->avg('rating'), 1) }}
                </div>

                <div>

                    <div class="score-stars">
                        ★★★★★
                    </div>

                    <small>
                        {{ $googleReviews->count() }} reviews
                    </small>

                </div>

            </div>

        </div>

        <div class="google-review-filter">

            <a href="?sort=newest"
            class="{{ $sort == 'newest' || !$sort ? 'active' : '' }}">

                Terbaru

            </a>

            <a href="?sort=5star"
            class="{{ $sort == '5star' ? 'active' : '' }}">

                ⭐ 5

            </a>

            <a href="?sort=4star"
            class="{{ $sort == '4star' ? 'active' : '' }}">

                ⭐ 4

            </a>

            <a href="?sort=oldest"
            class="{{ $sort == 'oldest' ? 'active' : '' }}">

                Terlama

            </a>

        </div>

        <div class="google-review-grid">

            @foreach($googleReviews as $review)

                <div class="google-review-card">

                    <div class="google-review-top">

                        <div class="google-review-user">

                            <img
                                src="{{ $review->profile_photo }}"
                                alt="{{ $review->author_name }}"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($review->author_name) }}&background=random'"
                            >

                            <div>

                                <h4>
                                    {{ $review->author_name }}
                                </h4>

                                <span>

                                    @if($review->review_date)

                                        {{ Carbon::parse($review->review_date)->diffForHumans() }}

                                    @else

                                        Tidak diketahui

                                    @endif

                                </span>

                            </div>

                        </div>

                        <div class="google-review-icon">
                            <i class="fa-brands fa-google"></i>
                        </div>

                    </div>

                    <div class="google-review-stars">

                        @for($i = 0; $i < $review->rating; $i++)
                            ★
                        @endfor

                    </div>

                    <p class="google-review-text">

                        {{ Str::limit($review->review_text, 180) }}

                    </p>

                    {{-- FOTO REVIEW --}}
                    @if($review->review_images)

                        @php
                            $images = json_decode($review->review_images, true);
                        @endphp

                        @if($images && count($images))

                            <div class="google-review-images">

                                @foreach($images as $image)

                                    <img
                                        src="{{ $image }}"
                                        alt="Review Image"
                                    >

                                @endforeach

                            </div>

                        @endif

                    @endif

                </div>

            @endforeach

        </div>

    </div>

</section>