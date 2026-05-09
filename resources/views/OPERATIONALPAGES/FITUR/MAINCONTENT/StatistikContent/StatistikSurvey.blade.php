<div class="stats-page">

    {{-- HEADER --}}
    <div class="stats-header">

        <div>
            <h1>Statistik Survey</h1>

            <p>
                Analisis data survey customer Studio Prewed
            </p>
        </div>

        <div class="stats-badge">
            <i class="fas fa-chart-pie"></i>
            Survey Analytics
        </div>

    </div>

    {{-- TOGGLE --}}
    <div class="stats-toggle">

        <button class="toggle-btn active" id="showBeforeBtn">
            Sebelum Cleaning
        </button>

        <button class="toggle-btn" id="showAfterBtn">
            Setelah Cleaning
        </button>

    </div>

    {{-- BEFORE --}}
    <div id="beforeSection">

        {{-- CARDS --}}
        <div class="stats-grid-card">

            <div class="stats-card-mini">
                <span>Total Data</span>
                <h2>{{ $statsBefore['total'] }}</h2>
            </div>

            <div class="stats-card-mini">
                <span>Data Duplikat</span>
                <h2>{{ $statsBefore['duplikat'] }}</h2>
            </div>

            <div class="stats-card-mini">
                <span>Rata-rata Score</span>
                <h2>{{ $statsBefore['rata_score'] }}</h2>
            </div>

            <div class="stats-card-mini">
                <span>Feedback Kosong</span>
                <h2>{{ $statsBefore['feedback_kosong'] }}</h2>
            </div>

        </div>

        {{-- FAVORITE SERVICES --}}
        <div class="stats-box">

            <div class="box-header">
                <h3>Favorite Services</h3>
            </div>

            <div class="service-list">

                @foreach($favoriteBefore as $service => $total)

                    <div class="service-item">

                        <div class="service-info">
                            <span>{{ $service }}</span>
                        </div>

                        <div class="service-bar-wrap">

                            <div
                                class="service-bar"
                                style="width: {{ ($total / max($favoriteBefore)) * 100 }}%"
                            ></div>

                        </div>

                        <strong>{{ $total }}</strong>

                    </div>

                @endforeach

            </div>

        </div>

        {{-- SCORE DISTRIBUTION --}}
        <div class="stats-box">

            <div class="box-header">
                <h3>Distribusi Recommendation Score</h3>
            </div>

            <div class="score-grid">

                @foreach($scoreDistributionBefore as $score => $count)

                    <div class="score-card">

                        <span>Score {{ $score }}</span>

                        <h2>{{ $count }}</h2>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

    {{-- AFTER --}}
    <div id="afterSection" class="hidden">

        {{-- CARDS --}}
        <div class="stats-grid-card">

            <div class="stats-card-mini">
                <span>Total Data</span>
                <h2>{{ $statsAfter['total'] }}</h2>
            </div>

            <div class="stats-card-mini">
                <span>Data Duplikat</span>
                <h2>{{ $statsAfter['duplikat'] }}</h2>
            </div>

            <div class="stats-card-mini">
                <span>Rata-rata Score</span>
                <h2>{{ $statsAfter['rata_score'] }}</h2>
            </div>

            <div class="stats-card-mini">
                <span>Feedback Kosong</span>
                <h2>{{ $statsAfter['feedback_kosong'] }}</h2>
            </div>

        </div>

        {{-- FAVORITE SERVICES --}}
        <div class="stats-box">

            <div class="box-header">
                <h3>Favorite Services</h3>
            </div>

            <div class="service-list">

                @foreach($favoriteAfter as $service => $total)

                    <div class="service-item">

                        <div class="service-info">
                            <span>{{ $service }}</span>
                        </div>

                        <div class="service-bar-wrap">

                            <div
                                class="service-bar after"
                                style="width: {{ ($total / max($favoriteAfter)) * 100 }}%"
                            ></div>

                        </div>

                        <strong>{{ $total }}</strong>

                    </div>

                @endforeach

            </div>

        </div>

        {{-- SCORE --}}
        <div class="stats-box">

            <div class="box-header">
                <h3>Distribusi Recommendation Score</h3>
            </div>

            <div class="score-grid">

                @foreach($scoreDistributionAfter as $score => $count)

                    <div class="score-card">

                        <span>Score {{ $score }}</span>

                        <h2>{{ $count }}</h2>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

</div>