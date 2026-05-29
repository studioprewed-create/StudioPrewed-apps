<div class="page-header">
    <div>
        <h1>Dashboard Data Partnership</h1>
        <div class="subtitle">
            Monitoring seluruh partnership sistem studio prewed
        </div>
    </div>
</div>

<div class="stats-grid">

    {{-- Statistik Kinerja --}}
    <a href="{{ route('executive.dataPartnership') }}" class="stats-card performance">
        <div class="stats-top">
            <div class="stats-icon">
                <i class="fas fa-user-check"></i>
            </div>

            <div class="stats-text">
                <h3>Data Partnership</h3>
                <span>Data Partnership</span>
            </div>
        </div>

        <p>
            Data Partnership
        </p>

        <div class="stats-footer">
            <span>Buka Data</span>
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

    {{-- Statistik Katalog --}}
    <a href="{{ route('executive.kategoriPartnership') }}" class="stats-card catalog">
        <div class="stats-top">
            <div class="stats-icon">
                <i class="fas fa-images"></i>
            </div>

            <div class="stats-text">
                <h3>kategori</h3>
                <span>kategori Partnership</span>
            </div>
        </div>

        <p>
            menampilkan data kategori partnership
        </p>

        <div class="stats-footer">
            <span>Buka Data</span>
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

</div>