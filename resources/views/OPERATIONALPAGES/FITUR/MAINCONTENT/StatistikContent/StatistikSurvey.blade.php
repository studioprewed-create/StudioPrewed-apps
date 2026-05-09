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

        <div class="stats-box">

            <div class="box-header">

                <h3>Data Survey Terbaru</h3>

                <p>
                    20 data terbaru sebelum cleaning
                </p>

            </div>

            <div class="stats-table-wrap">

                <table class="stats-table">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Customer</th>
                            <th>Tanggal Foto</th>
                            <th>Favorite Services</th>
                            <th>Score</th>
                            <th>Feedback</th>
                            <th>Created</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($dataPageBefore as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>

                                    <div class="customer-info">

                                        <div class="customer-avatar">

                                            {{ strtoupper(substr($item->customer_name ?? 'U', 0, 1)) }}

                                        </div>

                                        <div>

                                            <strong>
                                                {{ $item->customer_name ?? 'Unknown' }}
                                            </strong>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    {{ $item->photo_date?->format('d M Y') ?? '-' }}

                                </td>

                                <td>

                                    <div class="service-badges">

                                        @if(!empty($item->favorite_services))

                                            @foreach($item->favorite_services as $service)

                                                <span class="service-badge">

                                                    {{ $service }}

                                                </span>

                                            @endforeach

                                        @else

                                            -

                                        @endif

                                    </div>

                                </td>

                                <td>

                                    <span class="score-badge">

                                        {{ $item->recommendation_score }}

                                    </span>

                                </td>

                                <td>

                                    {{ Str::limit($item->feedback, 40) ?? '-' }}

                                </td>

                                <td>

                                    {{ $item->created_at->format('d M Y H:i') }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7">

                                    Belum ada data survey

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

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

    <div class="stats-box">

    <div class="box-header">

        <h3>Data Setelah Cleaning</h3>

        <p>
            20 data terbaru setelah filter duplikat
        </p>

    </div>

    <div class="stats-table-wrap">

        <table class="stats-table">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Customer</th>
                    <th>Tanggal Foto</th>
                    <th>Favorite Services</th>
                    <th>Score</th>
                    <th>Feedback</th>
                    <th>Created</th>

                </tr>

            </thead>

            <tbody>

                @forelse($dataPageAfter as $index => $item)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>

                            <div class="customer-info">

                                <div class="customer-avatar">

                                    {{ strtoupper(substr($item->customer_name ?? 'U', 0, 1)) }}

                                </div>

                                <div>

                                    <strong>
                                        {{ $item->customer_name ?? 'Unknown' }}
                                    </strong>

                                </div>

                            </div>

                        </td>

                        <td>

                            {{ $item->photo_date?->format('d M Y') ?? '-' }}

                        </td>

                        <td>

                            <div class="service-badges">

                                @if(!empty($item->favorite_services))

                                    @foreach($item->favorite_services as $service)

                                        <span class="service-badge">

                                            {{ $service }}

                                        </span>

                                    @endforeach

                                @else

                                    -

                                @endif

                            </div>

                        </td>

                        <td>

                            <span class="score-badge after">

                                {{ $item->recommendation_score }}

                            </span>

                        </td>

                        <td>

                            {{ Str::limit($item->feedback, 40) ?? '-' }}

                        </td>

                        <td>

                            {{ $item->created_at->format('d M Y H:i') }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7">

                            Belum ada data survey

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>