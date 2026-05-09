<div class="page-header">
    <div>
        <h1>Dashboard Statistik</h1>
        <div class="subtitle">
            Monitoring seluruh statistik sistem studio prewed
        </div>
    </div>
</div>

<div class="stats-grid">

    {{-- Statistik Survey --}}
    <a href="{{ route('executive.statistiksurvey') }}" class="stats-card survey">
        <div class="stats-top">
            <div class="stats-icon">
                <i class="fas fa-chart-line"></i>
            </div>

            <div class="stats-text">
                <h3>Statistik Survey</h3>
                <span>Analisis survey pelanggan</span>
            </div>
        </div>

        <p>
            Menampilkan data kepuasan pelanggan,
            layanan favorit, rekomendasi customer,
            dan grafik hasil survey.
        </p>

        <div class="stats-footer">
            <span>Buka Statistik</span>
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

    {{-- Statistik Review --}}
    <a href="{{ route('executive.statistikreview') }}" class="stats-card review">
        <div class="stats-top">
            <div class="stats-icon">
                <i class="fas fa-comments"></i>
            </div>

            <div class="stats-text">
                <h3>Statistik Review</h3>
                <span>Feedback dan rating</span>
            </div>
        </div>

        <p>
            Analisis review customer,
            rating pelayanan,
            dan performa kualitas studio.
        </p>

        <div class="stats-footer">
            <span>Buka Statistik</span>
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

    {{-- Statistik Pengeluaran --}}
    <a href="{{ route('executive.statistikpengeluaran') }}" class="stats-card expense">
        <div class="stats-top">
            <div class="stats-icon">
                <i class="fas fa-wallet"></i>
            </div>

            <div class="stats-text">
                <h3>Statistik Pengeluaran</h3>
                <span>Biaya operasional studio</span>
            </div>
        </div>

        <p>
            Monitoring pengeluaran,
            operasional harian,
            dan biaya produksi studio.
        </p>

        <div class="stats-footer">
            <span>Buka Statistik</span>
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

    {{-- Statistik Pendapatan --}}
    <a href="{{ route('executive.statistikpendapatan') }}" class="stats-card income">
        <div class="stats-top">
            <div class="stats-icon">
                <i class="fas fa-coins"></i>
            </div>

            <div class="stats-text">
                <h3>Statistik Pendapatan</h3>
                <span>Pemasukan dan profit</span>
            </div>
        </div>

        <p>
            Statistik pemasukan studio,
            profit bisnis,
            dan performa penjualan layanan.
        </p>

        <div class="stats-footer">
            <span>Buka Statistik</span>
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

    {{-- Statistik Kinerja --}}
    <a href="{{ route('executive.statistikkinerja') }}" class="stats-card performance">
        <div class="stats-top">
            <div class="stats-icon">
                <i class="fas fa-user-check"></i>
            </div>

            <div class="stats-text">
                <h3>Statistik Kinerja</h3>
                <span>Performa pegawai</span>
            </div>
        </div>

        <p>
            Monitoring produktivitas,
            efektivitas kerja,
            dan performa tim studio.
        </p>

        <div class="stats-footer">
            <span>Buka Statistik</span>
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

    {{-- Statistik Katalog --}}
    <a href="{{ route('executive.statistikkatalog') }}" class="stats-card catalog">
        <div class="stats-top">
            <div class="stats-icon">
                <i class="fas fa-images"></i>
            </div>

            <div class="stats-text">
                <h3>Statistik Katalog</h3>
                <span>Data paket dan layanan</span>
            </div>
        </div>

        <p>
            Menampilkan performa katalog,
            paket favorit,
            dan aktivitas customer.
        </p>

        <div class="stats-footer">
            <span>Buka Statistik</span>
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

</div>