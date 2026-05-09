<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">
            Dashboard Statistik
        </h2>

        <p class="text-muted mb-0">
            Monitoring seluruh data statistik sistem secara visual dan terstruktur.
        </p>
    </div>

    {{-- Card Statistik --}}
    <div class="row g-4">

        {{-- Statistik Survey --}}
        <div class="col-xl-4 col-md-6">
            <a href="{{ route('statistiksurvey') }}" class="text-decoration-none">
                <div class="stat-card survey-card">

                    <div class="card-top">
                        <div class="icon-wrap">
                            <i class="bi bi-bar-chart-line-fill"></i>
                        </div>

                        <div>
                            <h4>Statistik Survey</h4>
                            <span>Data hasil survey pelanggan</span>
                        </div>
                    </div>

                    <div class="card-detail">
                        <p>
                            Menampilkan statistik kepuasan pelanggan, rekomendasi,
                            layanan favorit, grafik survey, dan analisis data customer.
                        </p>
                    </div>

                    <div class="card-footer-custom">
                        <span>Lihat Statistik</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>

                </div>
            </a>
        </div>

        {{-- Statistik Review --}}
        <div class="col-xl-4 col-md-6">
            <a href="{{ route('statistikreview') }}" class="text-decoration-none">
                <div class="stat-card review-card">

                    <div class="card-top">
                        <div class="icon-wrap">
                            <i class="bi bi-chat-left-text-fill"></i>
                        </div>

                        <div>
                            <h4>Statistik Review</h4>
                            <span>Review dan penilaian customer</span>
                        </div>
                    </div>

                    <div class="card-detail">
                        <p>
                            Analisis review pelanggan, rating layanan,
                            komentar customer, dan performa kualitas pelayanan.
                        </p>
                    </div>

                    <div class="card-footer-custom">
                        <span>Lihat Statistik</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>

                </div>
            </a>
        </div>

        {{-- Statistik Pengeluaran --}}
        <div class="col-xl-4 col-md-6">
            <a href="{{ route('statistikpengeluaran') }}" class="text-decoration-none">
                <div class="stat-card expense-card">

                    <div class="card-top">
                        <div class="icon-wrap">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                        <div>
                            <h4>Statistik Pengeluaran</h4>
                            <span>Data biaya operasional</span>
                        </div>
                    </div>

                    <div class="card-detail">
                        <p>
                            Monitoring pengeluaran operasional,
                            biaya produksi, transaksi keluar,
                            dan analisis pengeluaran bisnis.
                        </p>
                    </div>

                    <div class="card-footer-custom">
                        <span>Lihat Statistik</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>

                </div>
            </a>
        </div>

        {{-- Statistik Pendapatan --}}
        <div class="col-xl-4 col-md-6">
            <a href="{{ route('statistikpendapatan') }}" class="text-decoration-none">
                <div class="stat-card income-card">

                    <div class="card-top">
                        <div class="icon-wrap">
                            <i class="bi bi-wallet2"></i>
                        </div>

                        <div>
                            <h4>Statistik Pendapatan</h4>
                            <span>Pemasukan dan profit bisnis</span>
                        </div>
                    </div>

                    <div class="card-detail">
                        <p>
                            Menampilkan total pendapatan,
                            grafik pemasukan, keuntungan bisnis,
                            dan performa penjualan.
                        </p>
                    </div>

                    <div class="card-footer-custom">
                        <span>Lihat Statistik</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>

                </div>
            </a>
        </div>

        {{-- Statistik Kinerja --}}
        <div class="col-xl-4 col-md-6">
            <a href="{{ route('statistikkinerja') }}" class="text-decoration-none">
                <div class="stat-card performance-card">

                    <div class="card-top">
                        <div class="icon-wrap">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>

                        <div>
                            <h4>Statistik Kinerja</h4>
                            <span>Performa pegawai dan sistem</span>
                        </div>
                    </div>

                    <div class="card-detail">
                        <p>
                            Analisis performa pegawai,
                            produktivitas kerja, efektivitas sistem,
                            dan pencapaian operasional.
                        </p>
                    </div>

                    <div class="card-footer-custom">
                        <span>Lihat Statistik</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>

                </div>
            </a>
        </div>

        {{-- Statistik Katalog --}}
        <div class="col-xl-4 col-md-6">
            <a href="{{ route('statistikkatalog') }}" class="text-decoration-none">
                <div class="stat-card catalog-card">

                    <div class="card-top">
                        <div class="icon-wrap">
                            <i class="bi bi-images"></i>
                        </div>

                        <div>
                            <h4>Statistik Katalog</h4>
                            <span>Data katalog dan layanan</span>
                        </div>
                    </div>

                    <div class="card-detail">
                        <p>
                            Menampilkan performa katalog,
                            layanan populer, paket favorit,
                            dan aktivitas customer pada katalog.
                        </p>
                    </div>

                    <div class="card-footer-custom">
                        <span>Lihat Statistik</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>

                </div>
            </a>
        </div>

    </div>

</div>
