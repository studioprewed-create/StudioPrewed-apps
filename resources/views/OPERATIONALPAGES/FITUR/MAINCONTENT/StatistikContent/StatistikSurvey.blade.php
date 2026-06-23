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

    <div class="stats-filter">

        <form id="surveyFilterForm">

            <div class="filter-grid">

                {{-- SEARCH --}}
                <div class="filter-group">

                    <input
                        type="text"
                        name="search"
                        placeholder="Cari customer..."
                        value="{{ request('search') }}"
                    >

                </div>

                <div class="filter-group">

                    <select name="created_month">

                        <option value="">
                            Bulan Submit
                        </option>

                        @for($m = 1; $m <= 12; $m++)

                            <option
                                value="{{ $m }}"
                                {{ request('created_month') == $m ? 'selected' : '' }}
                            >

                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}

                            </option>

                        @endfor

                    </select>

                </div>

                <div class="filter-group">

                    <select name="photo_month">

                        <option value="">
                            Bulan Foto
                        </option>

                        @for($m = 1; $m <= 12; $m++)

                            <option
                                value="{{ $m }}"
                                {{ request('photo_month') == $m ? 'selected' : '' }}
                            >

                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}

                            </option>

                        @endfor

                    </select>

                </div>

                {{-- SCORE --}}
                <div class="filter-group">

                    <select name="score">

                        <option value="">
                            Semua Score
                        </option>

                        @for($i = 1; $i <= 10; $i++)

                            <option
                                value="{{ $i }}"
                                {{ request('score') == $i ? 'selected' : '' }}
                            >

                                Score {{ $i }}

                            </option>

                        @endfor

                    </select>

                </div>

                {{-- SERVICE --}}
                <div class="filter-group">

                    <select name="service">

                        <option value="">
                            Semua Service
                        </option>

                        @foreach($services as $service)

                            <option
                                value="{{ $service }}"
                                {{ request('service') == $service ? 'selected' : '' }}
                            >

                                {{ $service }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="filter-group">

                    <select name="future_service">

                        <option value="">
                            Semua Future Service
                        </option>

                        @foreach($futureServices as $future)

                            <option
                                value="{{ $future }}"
                                {{ request('future_service') == $future ? 'selected' : '' }}
                            >

                                {{ $future }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="filter-btn">

                    <i class="fas fa-search"></i>

                    Filter

                </button>

            </div>

        </form>

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
                                style="width:{{max($favoriteBefore) > 0? ($total / max($favoriteBefore)) * 100: 0}}%"
                            ></div>

                        </div>

                        <strong>{{ $total }}</strong>

                    </div>

                @endforeach

            </div>

        </div>

        <div class="stats-box">

            <div class="box-header">
                <h3>Future Services</h3>
            </div>

            <div class="future-list">

                @foreach($futureBefore as $future => $total)

                    <div class="service-item">

                        <div class="service-info">
                            <span>{{ $future }}</span>
                        </div>

                        <div class="service-bar-wrap">

                            <div
                                class="service-bar"
                                style="width:{{ max($futureBefore) > 0 ? ($total / max($futureBefore)) * 100 : 0 }}%"
                            ></div>

                        </div>

                        <strong>{{ $total }}</strong>

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

                                    {{ Str::limit($item->feedback, 40) }}

                                    @if(strlen($item->feedback) > 40)
                                        <a href="#"
                                            class="feedback-readmore"
                                            data-feedback="{{ $item->feedback }}">
                                            Read More
                                        </a>
                                    @endif

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

        @if($dataPageBefore->hasPages())

            <div class="pagination-wrap">

                <div class="custom-pagination">

                    {{-- PREV --}}
                    @if($dataPageBefore->onFirstPage())

                        <span class="page-btn disabled">

                            <i class="fas fa-chevron-left"></i>

                        </span>

                    @else

                        <a
                            href="{{ $dataPageBefore->previousPageUrl() }}"
                            class="page-btn"
                        >

                            <i class="fas fa-chevron-left"></i>

                        </a>

                    @endif

                    {{-- PAGE --}}
                    @foreach($dataPageBefore->getUrlRange(1, $dataPageBefore->lastPage()) as $page => $url)

                        <a
                            href="{{ $url }}"
                            class="page-btn {{ $page == $dataPageBefore->currentPage() ? 'active' : '' }}"
                        >

                            {{ $page }}

                        </a>

                    @endforeach

                    {{-- NEXT --}}
                    @if($dataPageBefore->hasMorePages())

                        <a
                            href="{{ $dataPageBefore->nextPageUrl() }}"
                            class="page-btn"
                        >

                            <i class="fas fa-chevron-right"></i>

                        </a>

                    @else

                        <span class="page-btn disabled">

                            <i class="fas fa-chevron-right"></i>

                        </span>

                    @endif

                </div>

            </div>

        @endif

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
                                style="width:{{max($favoriteAfter) > 0? ($total / max($favoriteAfter)) * 100: 0}}%"
                            ></div>

                        </div>

                        <strong>{{ $total }}</strong>

                    </div>

                @endforeach

            </div>

        </div>

        <div class="stats-box">

            <div class="box-header">
                <h3>Future Services</h3>
            </div>

            <div class="future-list">

                @foreach($futureAfter as $future => $total)

                    <div class="service-item">

                        <div class="service-info">
                            <span>{{ $future }}</span>
                        </div>

                        <div class="service-bar-wrap">

                            <div
                                class="service-bar after"
                                style="width:{{ max($futureAfter) > 0 ? ($total / max($futureAfter)) * 100 : 0 }}%"
                            ></div>

                        </div>

                        <strong>{{ $total }}</strong>

                    </div>

                @endforeach

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

                                   {{ Str::limit($item->feedback, 40) }}

                                    @if(Str::length($item->feedback) > 40)
                                        <a href="#"
                                            class="feedback-readmore"
                                            data-feedback="{{ e($item->feedback) }}">
                                            Read More
                                        </a>
                                    @endif

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

        @if($dataPageAfter->hasPages())

            <div class="pagination-wrap">

                <div class="custom-pagination">

                    {{-- PREV --}}
                    @if($dataPageAfter->onFirstPage())

                        <span class="page-btn disabled">

                            <i class="fas fa-chevron-left"></i>

                        </span>

                    @else

                        <a
                            href="{{ $dataPageAfter->previousPageUrl() }}"
                            class="page-btn"
                        >

                            <i class="fas fa-chevron-left"></i>

                        </a>

                    @endif

                    {{-- PAGE --}}
                    @foreach($dataPageAfter->getUrlRange(1, $dataPageAfter->lastPage()) as $page => $url)

                        <a
                            href="{{ $url }}"
                            class="page-btn {{ $page == $dataPageAfter->currentPage() ? 'active' : '' }}"
                        >

                            {{ $page }}

                        </a>

                    @endforeach

                    {{-- NEXT --}}
                    @if($dataPageAfter->hasMorePages())

                        <a
                            href="{{ $dataPageAfter->nextPageUrl() }}"
                            class="page-btn"
                        >

                            <i class="fas fa-chevron-right"></i>

                        </a>

                    @else

                        <span class="page-btn disabled">

                            <i class="fas fa-chevron-right"></i>

                        </span>

                    @endif

                </div>

            </div>

        @endif

    </div>
</div>

<div class="custom-modal-backdrop" id="backdropFeedback"></div>

<div class="custom-modal" id="modalFeedback">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Customer Feedback</h5>

            <button type="button" class="btn btn-secondary" id="btnCloseFeedback">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <div id="feedbackContent"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCloseFeedback2">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>