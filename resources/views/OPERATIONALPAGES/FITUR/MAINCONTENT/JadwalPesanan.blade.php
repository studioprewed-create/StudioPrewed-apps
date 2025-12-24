    <div class="page-header">
        <div>
            <h1>Jadwal Pesanan</h1>
            <div class="subtitle">Kelola booking prewedding studio</div>
        </div>
    </div>

    {{-- FILTER TANGGAL (KALENDER + SLOT PREVIEW) --}}
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
                <div class="jp-cal-grid" id="jpCalGrid">
                    {{-- Tanggal akan di-render oleh JS --}}
                </div>
                <div class="jp-cal-footer">
                    <div class="jp-pill">
                        <span>Tanggal dipilih:</span>
                        <strong id="jpSelectedDateLabel"></strong>
                    </div>
                    <button type="button" class="jp-btn jp-btn-ghost" id="jpTodayBtn">Hari ini</button>
                </div>
            </div>

            {{-- SLOT WAKTU --}}
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
            @foreach ($bookings as $booking)
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">{{ $booking->kode_pesanan }}</span>
                        <span class="order-status status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    </div>
                    <div class="order-details">
                        <div class="detail-group">
                            <span class="detail-label">Klien</span>
                            <span class="detail-value">{{ $booking->nama_gabungan }}</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Tanggal</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($booking->photoshoot_date)->format('d M Y') }}</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">WhatsApp</span>
                            <span class="detail-value">{{ $booking->phone_gabungan }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
                    <div class="order-actions">
                        <button class="ticket-action" data-modal-target="#bookingModal-{{ $booking->id }}">
                            Lihat Detail
                        </button>
                        <div class="booking-modal" id="bookingModal-{{ $booking->id }}">
                            <div class="booking-modal-backdrop"></div>

                            <div class="booking-modal-content">
                                <button class="booking-modal-close">&times;</button>

                                <h3>Detail Booking</h3>

                                <div class="modal-grid">
                                    <div><strong>Kode Pesanan</strong><br>#{{ $booking->kode_pesanan }}</div>
                                    <div><strong>Status</strong><br>{{ ucfirst($booking->status) }}</div>

                                    <div><strong>Paket</strong><br>{{ $booking->package->nama_paket ?? '-' }}</div>
                                    <div><strong>Style</strong><br>{{ $booking->style ?? '-' }}</div>

                                    <div><strong>Tanggal</strong><br>{{ $booking->photoshoot_date->format('d F Y') }}</div>
                                    <div><strong>Slot</strong><br>{{ $booking->photoshoot_slot }}</div>

                                    <div><strong>CPP</strong><br>{{ $booking->nama_cpp }}</div>
                                    <div><strong>CPW</strong><br>{{ $booking->nama_cpw }}</div>

                                    <div><strong>Tema Utama</strong><br>{{ $booking->tema_nama }} ({{ $booking->tema_kode }})</div>
                                    <div><strong>Tema Tambahan</strong><br>{{ $booking->tema2_nama ?? '-' }}</div>

                                    <div><strong>Addon</strong><br>{{ $booking->addons_total_formatted }}</div>
                                    <div><strong>Total</strong><br>{{ $booking->grand_total_formatted }}</div>

                                    <div style="grid-column:1/-1">
                                        <strong>Catatan</strong><br>
                                        {{ $booking->notes ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>