<div class="page-header">
    <div>
        <h1>Jadwal Pesanan</h1>
        <div class="subtitle">Kelola booking prewedding studio</div>
    </div>
</div>

<section class="jp-filter-wrap" id="jpFilter">
    <input type="hidden" id="jpSelectedDate" value="{{ $selectedDate }}">

    <div class="jp-scheduler">
        {{-- KALENDER --}}
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
            <div class="jp-cal-grid" id="jpCalGrid"></div>
            <div class="jp-cal-footer">
                <div class="jp-pill">
                    <span>Tanggal dipilih:</span>
                    <strong id="jpSelectedDateLabel"></strong>
                </div>
                <button type="button" class="jp-btn jp-btn-ghost" id="jpTodayBtn">Hari ini</button>
            </div>
        </div>

        {{-- SLOT --}}
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

    {{-- DATA PESANAN --}}
    <div class="orders-grid" id="ordersGrid">
        @forelse ($bookings as $booking)
            <div class="order-card">

                <div class="order-header">
                    <span class="order-id">{{ $booking->kode_pesanan }}</span>
                    <span class="order-status status-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <div class="order-details">
                    <div class="detail-group">
                        <span class="detail-label">Klien</span>
                        <span class="detail-value">{{ $booking->nama_gabungan }}</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">Tanggal</span>
                        <span class="detail-value">
                            {{ $booking->photoshoot_date->format('d M Y') }}
                        </span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">WhatsApp</span>
                        <span class="detail-value">{{ $booking->phone_gabungan }}</span>
                    </div>
                </div>

                <div class="order-actions">
                    <button class="ticket-action"
                            data-modal-target="bookingModal-{{ $booking->id }}">
                        Lihat Detail
                    </button>
                </div>

            </div>
        @empty
            <div class="empty-state">Tidak ada booking pada tanggal ini</div>
        @endforelse
    </div>
    @foreach ($bookings as $booking)
        <div class="booking-modal" id="bookingModal-{{ $booking->id }}">
            <div class="booking-modal-backdrop"></div>

            <div class="booking-modal-content modal-xl">

                <div class="modal-header">
                    <h5>Detail Booking</h5>
                    <button class="booking-modal-close">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-grid-2">

                        <div class="form-group">
                            <strong>Kode Pesanan</strong>
                            <div>#{{ $booking->kode_pesanan }}</div>
                        </div>

                        <div class="form-group">
                            <strong>Status</strong>
                            <div>{{ ucfirst($booking->status) }}</div>
                        </div>

                        <div class="form-group">
                            <strong>Paket</strong>
                            <div>{{ $booking->package->nama_paket ?? '-' }}</div>
                        </div>

                        <div class="form-group">
                            <strong>Style</strong>
                            <div>{{ $booking->style ?? '-' }}</div>
                        </div>

                        <div class="form-group">
                            <strong>Tanggal</strong>
                            <div>{{ $booking->photoshoot_date->format('d F Y') }}</div>
                        </div>

                        <div class="form-group">
                            <strong>Slot</strong>
                            <div>{{ $booking->photoshoot_slot }}</div>
                        </div>

                        <div class="form-group">
                            <strong>CPP</strong>
                            <div>{{ $booking->nama_cpp }}</div>
                        </div>

                        <div class="form-group">
                            <strong>CPW</strong>
                            <div>{{ $booking->nama_cpw }}</div>
                        </div>

                        <div class="form-group">
                            <strong>Tema Utama</strong>
                            <div>{{ $booking->tema_nama }}</div>
                        </div>

                        <div class="form-group">
                            <strong>Tema Tambahan</strong>
                            <div>{{ $booking->tema2_nama ?? '-' }}</div>
                        </div>

                        <div class="form-group">
                            <strong>Addon</strong>
                            <div>{{ $booking->addons_total_formatted }}</div>
                        </div>

                        <div class="form-group">
                            <strong>Total</strong>
                            <div>{{ $booking->grand_total_formatted }}</div>
                        </div>

                        <div class="form-group" style="grid-column:1/-1">
                            <strong>Catatan</strong>
                            <div>{{ $booking->notes ?? '-' }}</div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="jp-btn jp-btn-ghost booking-modal-close">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endforeach
</section>
