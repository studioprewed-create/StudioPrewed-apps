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

            <!-- ================= HEADER ================= -->
            <div class="modal-header">
            <h5>Booking Baru</h5>
            <button type="button" class="btn btn-secondary" id="btnCloseBookingCreate">✕</button>
            </div>

            <!-- ================= BODY ================= -->
            <div class="modal-body">

            <form method="POST"
                action="{{ route('executive.homepages.store', ['section' => 'bookingexecutive']) }}">
                @csrf

                <div class="booking-container">

                <h2>Form Booking Prewed (Admin)</h2>

                <!-- ================= CPP & CPW ================= -->
                <div class="grid-2">
                    <div class="card-section">
                    <h4>CPP</h4>
                    <label>Nama</label>
                    <input name="nama_cpp" required>

                    <label>Email</label>
                    <input name="email_cpp" type="email">

                    <label>No. Telp</label>
                    <input name="phone_cpp" required>

                    <label>Alamat</label>
                    <input name="alamat_cpp">

                    <label>Instagram</label>
                    <input name="ig_cpp">

                    <label>TikTok</label>
                    <input name="tiktok_cpp">
                    </div>

                    <div class="card-section">
                    <h4>CPW</h4>
                    <label>Nama</label>
                    <input name="nama_cpw" required>

                    <label>Email</label>
                    <input name="email_cpw" type="email">

                    <label>No. Telp</label>
                    <input name="phone_cpw" required>

                    <label>Alamat</label>
                    <input name="alamat_cpw">

                    <label>Instagram</label>
                    <input name="ig_cpw">

                    <label>TikTok</label>
                    <input name="tiktok_cpw">
                    </div>
                </div>

                <div class="card-section" style="margin-top:20px">
                    <label>Wedding Date (opsional)</label>
                    <input
                        type="date"
                        name="wedding_date"
                        id="wedding_date"
                        min="{{ now()->toDateString() }}"
                    >

                    <label style="margin-top:12px">Notes (opsional)</label>
                    <textarea
                        name="notes"
                        id="notes"
                        placeholder="Catatan tambahan"
                        rows="3"
                    ></textarea>
                </div>

                <!-- ================= DETAIL BOOKING ================= -->
                <h4 class="section-title">Detail Booking</h4>

                <label>Paket</label>
                <select name="package_id" id="package_id" required>
                    <option value="">-- pilih paket --</option>
                    @foreach($packages as $pkg)
                    <option value="{{ $pkg->id }}" data-durasi="{{ (int) $pkg->durasi }}">
                        {{ $pkg->nama_paket }} ({{ (int) $pkg->durasi }} menit)
                    </option>
                    @endforeach
                </select>

                <div class="grid-2">
                    <div>
                    <label>Tanggal Photoshoot</label>
                    <input type="date" name="photoshoot_date" id="photoshoot_date"
                            min="{{ now()->toDateString() }}" required>
                    </div>

                    <div>
                    <label>Style</label>
                    <select name="style" id="style" required>
                        <option value="">-- pilih style --</option>
                        <option value="Hijab">Hijab</option>
                        <option value="HairDo">HairDo</option>
                    </select>
                    </div>
                </div>

                <!-- ================= SLOT ================= -->
                <label>Pilih Slot Waktu</label>
                <div id="slotList" class="slots-grid">
                    <p style="opacity:.7">Pilih paket & tanggal untuk melihat slot.</p>
                </div>

                <!-- Hidden slot result -->
                <input type="hidden" name="slot_code">
                <input type="hidden" name="start_time">
                <input type="hidden" name="end_time">
                <input type="hidden" name="extra_slot_code">
                <input type="hidden" name="extra_start_time">
                <input type="hidden" name="extra_end_time">
                <input type="hidden" name="extra_minutes">

                <input type="hidden" name="tema2_kode">
                <input type="hidden" name="tema2_nama">

                <div id="addonHiddenBag"></div>

                <!-- ================= TEMA ================= -->
                <h4 class="section-title">Tema Baju Utama (opsional)</h4>

                <div class="grid-2">
                    <div>
                    <label>Nama Tema</label>
                    <select id="tema_nama" name="tema_nama">
                        <option value="">-- pilih nama tema --</option>
                        @foreach($temas->groupBy('nama') as $nama => $list)
                            <option value="{{ $list->first()->id }}">
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                    </div>

                    <div>
                    <label>Kode Tema</label>
                    <select id="tema_kode" name="tema_kode" disabled>
                        <option value="">-- pilih kode tema --</option>
                        @foreach($temas as $t)
                        <option
                            value="{{ $t->kode }}"
                            data-tema-id="{{ $t->id }}">
                            {{ $t->kode }} - {{ $t->nama }}
                        </option>
                        @endforeach
                    </select>
                    </div>
                </div>

                <div class="addon-section" style="margin-top:32px">
                    <h4 style="margin-bottom:10px">Addon (Opsional)</h4>
                    <p style="opacity:.7;font-size:12px">
                        Addon dapat menambah slot waktu atau tema baju tambahan.
                    </p>

                    <div class="addons-grid">
                        @foreach ($addonGroups as $kategori => $group)
                        <div class="addon-group">
                            <h5>{{ $group->first()->kategori_label }}</h5>

                            @foreach ($group as $addon)
                            <label class="addon-item">
                                <input
                                    type="checkbox"
                                    class="addon-check"
                                    data-id="{{ $addon->id }}"
                                    data-kategori="{{ $addon->kategori }}"
                                    data-harga="{{ $addon->harga }}"
                                    data-durasi="{{ $addon->durasi ?? 0 }}"
                                >
                                <span>{{ $addon->nama }}</span>
                                <small>{{ $addon->durasi_label }}</small>
                                <b>Rp {{ number_format($addon->harga,0,',','.') }}</b>
                            </label>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- SLOT TAMBAHAN -->
                <div id="extraSlotWrapper" style="display:none;margin-top:20px">
                    <h4>Slot Tambahan</h4>
                    <div id="extraSlotList" class="slots-grid"></div>
                </div>

                <!-- TEMA TAMBAHAN -->
                <div id="extraTemaWrapper" style="display:none;margin-top:20px">
                    <h4>Tema Tambahan</h4>
                    <div class="grid-3">
                        <div>
                            <label>Nama Tema Tambahan</label>
                            <select id="tema2_nama">
                                <option value="">-- pilih --</option>
                                @foreach($temas->groupBy('nama') as $nama => $list)
                                    <option value="{{ $nama }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Kode Tema Tambahan</label>
                            <select id="tema2_kode" disabled>
                                <option value="">-- pilih --</option>
                                @foreach($temas as $t)
                                    <option
                                        value="{{ $t->kode }}"
                                        data-nama="{{ $t->nama }}"
                                        data-id="{{ $t->id }}">
                                        {{ $t->kode }} - {{ $t->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                </div><!-- /booking-container -->

                <!-- ================= FOOTER (DI DALAM FORM) ================= -->
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Booking</button>
                <button type="button" class="btn btn-secondary" id="btnCloseBookingCreate2">Tutup</button>
                </div>

            </form>
            </div><!-- /modal-body -->

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
