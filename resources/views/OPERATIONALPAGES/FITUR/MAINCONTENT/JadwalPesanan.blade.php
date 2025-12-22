{{-- =========================================================
 PAGE HEADER
========================================================= --}}
<div class="page-header">
    <div>
        <h1>Jadwal Pesanan</h1>
        <div class="subtitle">Kelola booking prewedding studio</div>
    </div>
</div>

{{-- =========================================================
 FILTER JADWAL (KALENDER + SLOT PREVIEW)
========================================================= --}}
<section class="jp-filter-wrap" id="jpFilter">

    {{-- sumber kebenaran tanggal --}}
    <input type="hidden"
           id="jpSelectedDate"
           value="{{ now()->toDateString() }}">

    <div class="jp-scheduler">

        {{-- ================= KALENDER ================= --}}
        <div class="jp-card jp-calendar-card">

            <div class="jp-cal-head">
                <button type="button" class="jp-icon-btn" id="jpCalPrev">‹</button>
                <div class="jp-cal-label" id="jpCalLabel"></div>
                <button type="button" class="jp-icon-btn" id="jpCalNext">›</button>
            </div>

            <div class="jp-cal-week">
                <div>Min</div><div>Sen</div><div>Sel</div>
                <div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
            </div>

            <div class="jp-cal-grid" id="jpCalGrid">
                {{-- tanggal di-render JS --}}
            </div>

            <div class="jp-cal-footer">
                <div class="jp-pill">
                    <span>Tanggal dipilih:</span>
                    <strong id="jpSelectedDateLabel"></strong>
                </div>

                <button type="button" class="jp-btn jp-btn-ghost" id="jpTodayBtn">
                    Hari ini
                </button>
            </div>

        </div>

        {{-- ================= SLOT PREVIEW ================= --}}
        <div class="jp-card jp-slots-card">

            <div class="jp-slots-head">
                <h4>Slot Waktu Tersedia</h4>
                <small>Otomatis berdasarkan tanggal (2 Studio)</small>
            </div>

            <div class="jp-studio">
                <h5>Studio 1</h5>
                <div class="slots-grid" id="jpStudio1"></div>
            </div>

            <div class="jp-studio">
                <h5>Studio 2</h5>
                <div class="slots-grid" id="jpStudio2"></div>
            </div>

        </div>

    </div>
</section>
