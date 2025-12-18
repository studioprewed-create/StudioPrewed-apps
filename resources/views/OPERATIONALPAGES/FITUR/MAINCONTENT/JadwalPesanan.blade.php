<div class="content">
    <div class="page-header">
        <h1>Manajemen Pesanan</h1>
        <div class="header-actions">
            <button type="button" class="btn btn-secondary js-toggle-filter">
                <i class="fas fa-filter"></i> Filter
            </button>

            <button type="button" class="btn btn-primary js-open-booking-modal">
                <i class="fas fa-plus"></i> Booking Baru
            </button>
        </div>
    </div>

    {{-- Filter Section --}}
    <form class="filter-section" method="GET"
          action="{{ route('executive.page', ['page' => 'JadwalPesanan']) }}">
        <div class="filter-grid">
            <div class="filter-group">
                <label for="status-filter">Status</label>
                @php
                    $currentStatus = request('status', 'all');
                @endphp
                <select id="status-filter" name="status">
                    <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $currentStatus === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="completed" {{ $currentStatus === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="canceled" {{ $currentStatus === 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="date-filter">Tanggal Booking</label>
                <input type="date"
                       id="date-filter"
                       name="date"
                       value="{{ $selectedDate }}">
            </div>

            <div class="filter-group">
                <label for="search">Cari</label>
                <input type="text"
                       id="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kode, nama, atau nomor...">
            </div>
        </div>

        <div class="filter-actions">
            <a href="{{ route('executive.page', ['page' => 'JadwalPesanan']) }}"
               class="btn btn-secondary">
                <i class="fas fa-sync"></i> Reset
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Terapkan
            </button>
        </div>
    </form>

    {{-- Orders Grid --}}
    <div class="orders-grid">
        @php
            $statusLabelMap = [
                'submitted' => 'Pending',
                'confirmed' => 'Dikonfirmasi',
                'cancelled' => 'Dibatalkan',
                'completed' => 'Selesai',
            ];

            $statusClassMap = [
                'submitted' => 'status-pending',
                'confirmed' => 'status-confirmed',
                'cancelled' => 'status-canceled',
                'completed' => 'status-completed',
            ];
        @endphp

        @forelse($bookings as $booking)
            @php
                $dbStatus = $booking->status;
                $statusLabel = $statusLabelMap[$dbStatus] ?? $dbStatus;
                $statusClass = $statusClassMap[$dbStatus] ?? 'status-pending';

                $namaKlien = $booking->nama_gabungan
                    ?? trim(($booking->nama_cpp ?? '').' & '.($booking->nama_cpw ?? ''), ' &');

                $tgl = \Carbon\Carbon::parse($booking->photoshoot_date)->translatedFormat('d M Y');
                $jamMulai = \Illuminate\Support\Str::substr($booking->start_time, 0, 5);
                $jamSelesai = \Illuminate\Support\Str::substr($booking->end_time, 0, 5);

                $phoneWa = preg_replace('/\D/', '', $booking->phone_cpp ?: $booking->phone_cpw);
                if (\Illuminate\Support\Str::startsWith($phoneWa, '0')) {
                    $phoneWa = '62'.substr($phoneWa, 1);
                }
            @endphp

            <div class="order-card">
                <div class="order-header">
                    <span class="order-id">{{ $booking->kode_pesanan }}</span>
                    <span class="order-status {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>

                <div class="order-details">
                    <div class="detail-group">
                        <span class="detail-label">Klien</span>
                        <span class="detail-value">{{ $namaKlien }}</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">Tanggal</span>
                        <span class="detail-value">{{ $tgl }}</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">Jam</span>
                        <span class="detail-value">{{ $jamMulai }} - {{ $jamSelesai }}</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">WhatsApp</span>
                        <span class="detail-value">{{ $booking->phone_cpp ?? $booking->phone_cpw }}</span>
                    </div>
                    @if($booking->package)
                        <div class="detail-group">
                            <span class="detail-label">Paket</span>
                            <span class="detail-value">{{ $booking->package->nama_paket }}</span>
                        </div>
                    @endif
                </div>

                {{-- placeholder tim penugasan (nanti bisa diisi relasi) --}}
                <div class="order-team">
                    <div class="team-title">Tim Penugasan</div>
                    <div class="team-members">
                        <div class="team-member is-empty">
                            <div class="member-avatar">-</div>
                            <div class="member-info">
                                <div class="member-name">Belum ditugaskan</div>
                                <div class="member-role">–</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-actions">
                    {{-- Edit booking (buka modal edit nanti) --}}
                    <button type="button"
                            class="action-btn btn-edit js-open-booking-modal"
                            data-mode="edit"
                            data-id="{{ $booking->id }}"
                            data-nama_cpp="{{ $booking->nama_cpp }}"
                            data-phone_cpp="{{ $booking->phone_cpp }}"
                            data-nama_cpw="{{ $booking->nama_cpw }}"
                            data-phone_cpw="{{ $booking->phone_cpw }}"
                            data-date="{{ $booking->photoshoot_date }}"
                            data-start="{{ $jamMulai }}"
                            data-end="{{ $jamSelesai }}"
                            data-package_id="{{ $booking->package_id }}"
                            data-style="{{ $booking->style }}"
                            data-status="{{ $booking->status }}"
                            data-notes="{{ $booking->notes }}">
                        <i class="fas fa-edit"></i> Edit
                    </button>

                    {{-- WhatsApp --}}
                    @if($phoneWa)
                        <a href="https://wa.me/{{ $phoneWa }}?text={{ urlencode('Halo '.$namaKlien.', terkait booking '.$booking->kode_pesanan) }}"
                           target="_blank"
                           class="action-btn btn-whatsapp">
                            <i class="fab fa-whatsapp"></i> WA
                        </a>
                    @endif

                    {{-- Delete --}}
                    <form action="{{ route('executive.homepages.destroy', ['section' => 'bookingclient', 'id' => $booking->id]) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus booking ini?');"
                          style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="empty-state">
                Belum ada booking untuk tanggal ini.
            </p>
        @endforelse
    </div>

    {{-- (Opsional) Pagination: kalau nanti pakai ->paginate() tinggal aktifkan --}}
    {{-- <div class="pagination">
        {{ $bookings->links() }}
    </div> --}}
</div>

{{-- Floating Button --}}
<div class="floating-btn js-open-booking-modal" data-mode="create">
    <i class="fas fa-plus"></i>
</div>

{{-- MODAL Booking (Create / Edit) --}}
<div class="booking-modal-backdrop" id="bookingModal" aria-hidden="true">
    <div class="booking-modal">
        <div class="booking-modal-header">
            <h2 id="bookingModalTitle">Booking Baru</h2>
            <button type="button" class="booking-modal-close" data-close>
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- form create/update bookingclient --}}
        <form id="bookingForm"
              method="POST"
              action="{{ route('executive.homepages.store', ['section' => 'bookingclient']) }}">
            @csrf
            {{-- untuk edit kita inject @method('PUT') via JS --}}
            <div class="booking-modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Nama CPP</label>
                        <input type="text" name="nama_cpp" id="f_nama_cpp" required>
                    </div>
                    <div class="form-group">
                        <label>WhatsApp CPP</label>
                        <input type="text" name="phone_cpp" id="f_phone_cpp" required>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Nama CPW</label>
                        <input type="text" name="nama_cpw" id="f_nama_cpw" required>
                    </div>
                    <div class="form-group">
                        <label>WhatsApp CPW</label>
                        <input type="text" name="phone_cpw" id="f_phone_cpw" required>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label>Tanggal Photoshoot</label>
                        <input type="date" name="photoshoot_date" id="f_date" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" name="start_time" id="f_start" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="time" name="end_time" id="f_end" required>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Paket</label>
                        <select name="package_id" id="f_package_id">
                            <option value="">- Tanpa Paket -</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}">{{ $pkg->nama_paket }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Style</label>
                        <select name="style" id="f_style" required>
                            <option value="Hair">Hair</option>
                            <option value="HairDo">HairDo</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="f_status" required>
                            <option value="submitted">Pending</option>
                            <option value="confirmed">Dikonfirmasi</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="notes" id="f_notes" rows="3"></textarea>
                </div>
            </div>

            <div class="booking-modal-footer">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>