<div class="page-header">
    <div>
        <h1>Jadwal Pesanan</h1>
        <div class="subtitle">Kelola booking prewedding studio</div>
    </div>
</div>

<section class="jp-filter-wrap" id="jpFilter">
    <input type="hidden" id="jpSelectedDate" value="{{ $selectedDate }}">

    <div class="jp-scheduler">
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
            <div class="jp-studio">
            <button type="button" class="btn btn-primary" id="btnOpenBooking">
                + Booking Baru
            </button>
            </div>
        </div>
    </div>

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
                        <span class="detail-label">NamaCPP</span>
                        <span class="detail-value">{{ $booking->nama_cpp }}</span>
                    </div>
                     <div class="detail-group">
                        <span class="detail-label">NamaCPW</span>
                        <span class="detail-value">{{ $booking->nama_cpw }}</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">Tanggal booking</span>
                        <span class="detail-value">
                            {{ $booking->photoshoot_date->format('d M Y') }}
                        </span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">Nama Paket</span>
                        <span class="detail-value">
                            {{ $booking->package->nama_paket ?? '-' }}
                        </span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">Style</span>
                        <span class="detail-value">
                            {{ $booking->style }}
                        </span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">WhatsApp</span>
                        <span class="detail-value">{{ $booking->phone_gabungan }}</span>
                    </div>
                </div>

                <div class="order-actions">
                    <button
                        class="action-btn btn-view js-open-booking-modal"
                        data-kode="{{ $booking->kode_pesanan }}"
                        data-status="{{ ucfirst($booking->status) }}"
                        data-paket="{{ $booking->package->nama_paket ?? '-' }}"
                        data-style="{{ $booking->style ?? '-' }}"
                        data-tanggal="{{ $booking->photoshoot_date->format('d F Y') }}"
                        data-slot="{{ $booking->photoshoot_slot }}"
                        data-cpp="{{ $booking->nama_cpp }}"
                        data-cpw="{{ $booking->nama_cpw }}"
                        data-tema="{{ $booking->tema_nama }}"
                        data-tema2="{{ $booking->tema2_nama ?? '-' }}"
                        data-addon="{{ $booking->addons_total_formatted }}"
                        data-total="{{ $booking->grand_total_formatted }}"
                        data-notes="{{ $booking->notes ?? '-' }}"
                    >
                        Lihat Detail
                    </button>
                    <button
                        type="button"
                        class="action-btn btn-edit js-open-booking-edit"
                    >
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <form action="{{ route('executive.homepages.destroy', ['section' => 'bookingexecutive', 'id' => $booking->id]) }}"
                            method="POST"
                            style="display:inline-block"
                            onsubmit="return confirm('Yakin ingin menghapus booking ini?');">
                        @csrf
                        @method('DELETE')
                        <button class="action-btn btn-delete" type="submit">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="empty-state">
                Tidak ada booking pada tanggal ini
            </div>
        @endforelse
    </div>

    <div class="custom-modal-backdrop" id="bookingBackdrop"></div>
        <div class="custom-modal" id="bookingModal" aria-hidden="true">
            <div class="modal-content modal-xl">

                <div class="modal-header">
                    <h5>Detail Booking</h5>
                    <button class="btn btn-secondary" type="button" id="btnCloseBooking">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-grid-2">

                        <div class="form-group">
                            <strong>Kode Pesanan</strong>
                            <div id="b_kode"></div>
                        </div>

                        <div class="form-group">
                            <strong>Status</strong>
                            <div id="b_status"></div>
                        </div>

                        <div class="form-group">
                            <strong>Paket</strong>
                            <div id="b_paket"></div>
                        </div>

                        <div class="form-group">
                            <strong>Style</strong>
                            <div id="b_style"></div>
                        </div>

                        <div class="form-group">
                            <strong>Tanggal</strong>
                            <div id="b_tanggal"></div>
                        </div>

                        <div class="form-group">
                            <strong>Slot</strong>
                            <div id="b_slot"></div>
                        </div>

                        <div class="form-group">
                            <strong>CPP</strong>
                            <div id="b_cpp"></div>
                        </div>

                        <div class="form-group">
                            <strong>CPW</strong>
                            <div id="b_cpw"></div>
                        </div>

                        <div class="form-group">
                            <strong>Tema Utama</strong>
                            <div id="b_tema"></div>
                        </div>

                        <div class="form-group">
                            <strong>Tema Tambahan</strong>
                            <div id="b_tema2"></div>
                        </div>

                        <div class="form-group">
                            <strong>Addon</strong>
                            <div id="b_addon"></div>
                        </div>

                        <div class="form-group">
                            <strong>Total</strong>
                            <div id="b_total"></div>
                        </div>

                        <div class="form-group" style="grid-column:1/-1">
                            <strong>Catatan</strong>
                            <div id="b_notes"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="custom-modal-backdrop" id="bookingCreateBackdrop"></div>
        <div class="custom-modal" id="bookingCreateModal" aria-hidden="true">
            <div class="modal-content modal-xl">

                <div class="modal-header">
                <h5>Booking Baru</h5>
                <button class="btn btn-secondary" type="button" id="btnCloseBookingCreate">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                </div>

                <form method="POST"
                    action="{{ route('executive.homepages.store', ['section' => 'bookingexecutive']) }}">
                @csrf

                <div class="modal-body">
                    <div class="booking-container">

                    <h2>Form Booking Prewed (Admin)</h2>

                    <div class="grid-2">
                        <!-- CPP -->
                        <div>
                        <div class="step-head"><h4>CPP (Pria)</h4></div>
                        <label>Nama CPP</label>
                        <input name="nama_cpp" required>
                        <label>Email CPP</label>
                        <input name="email_cpp" type="email">
                        <label>No. Telp CPP</label>
                        <input name="phone_cpp" required>
                        <label>Alamat CPP</label>
                        <input name="alamat_cpp">
                        </div>

                        <!-- CPW -->
                        <div>
                        <div class="step-head"><h4>CPW (Perempuan)</h4></div>
                        <label>Nama CPW</label>
                        <input name="nama_cpw" required>
                        <label>Email CPW</label>
                        <input name="email_cpw" type="email">
                        <label>No. Telp CPW</label>
                        <input name="phone_cpw" required>
                        <label>Alamat CPW</label>
                        <input name="alamat_cpw">
                        </div>
                    </div>

                    <div class="step-head" style="margin-top:24px">
                        <h4>Detail Booking</h4>
                    </div>

                    <label>Paket</label>
                    <select name="package_id" id="package_id" required>
                        <option value="">-- pilih paket --</option>
                        @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}">{{ $pkg->nama_paket }}</option>
                        @endforeach
                    </select>

                    <div class="grid-2">
                        <div>
                        <label>Tanggal</label>
                        <input type="date" id="photoshoot_date" name="photoshoot_date" required>
                        </div>
                        <div>
                        <label>Status</label>
                        <select name="status" required>
                            <option value="submitted">Submitted</option>
                            <option value="confirmed">Confirmed</option>
                        </select>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div>
                        <label>Jam Mulai</label>
                        <input type="time" name="start_time" required>
                        </div>
                        <div>
                        <label>Jam Selesai</label>
                        <input type="time" name="end_time" required>
                        </div>
                    </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Simpan Booking</button>
                    <button class="btn btn-secondary" type="button" id="btnCloseBookingCreate2">
                    Tutup
                    </button>
                </div>

                </form>
            </div>
        </div>
    <div class="custom-modal-backdrop" id="bookingEditBackdrop"></div>
        <div class="custom-modal" id="bookingEditModal" aria-hidden="true">
            <div class="modal-content modal-xl">

                <div class="modal-header">
                    <h5>Edit Booking</h5>
                    <button
                        class="btn btn-secondary"
                        type="button"
                        id="btnCloseBookingEdit">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <p style="opacity:.7">
                        Form edit booking akan ditempatkan di sini.
                    </p>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-secondary"
                        type="button"
                        id="btnCloseBookingEdit2">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
</section>
