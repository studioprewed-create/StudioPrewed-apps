{{-- resources/views/OPERATIONALPAGES/PAGE/EXECUTIVE/JadwalPesanan.blade.php --}}
<div class="dashboard-content">

    {{-- Header --}}
    <div class="page-header">
        <h2>Manajemen Pesanan</h2>
        <p>Kelola jadwal pesanan pelanggan dan status booking.</p>
    </div>

    {{-- Toggle Filter --}}
    <button class="toggle-filter-btn js-toggle-filter">
        <span>Filter Pesanan</span>
        <i class="fas fa-filter"></i>
    </button>

    {{-- Filter Section --}}
    <div class="filter-section" style="display:none;">
        <form method="GET" action="{{ route('executive.page', ['page' => 'JadwalPesanan']) }}" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="submitted"  {{ request('status')==='submitted'  ? 'selected':'' }}>Submitted</option>
                        <option value="confirmed"  {{ request('status')==='confirmed'  ? 'selected':'' }}>Confirmed</option>
                        <option value="completed"  {{ request('status')==='completed'  ? 'selected':'' }}>Completed</option>
                        <option value="cancelled"  {{ request('status')==='cancelled'  ? 'selected':'' }}>Cancelled</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Tanggal</label>
                    <input type="date" name="date" value="{{ request('date', $selectedDate ?? now()->toDateString()) }}">
                </div>

                <div class="filter-group">
                    <label>Paket</label>
                    <select name="package_id">
                        <option value="">Semua Paket</option>
                        @foreach($packages as $p)
                            <option value="{{ $p->id }}" {{ (string)request('package_id')===(string)$p->id ? 'selected':'' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button class="btn-apply" type="submit">Terapkan Filter</button>
                <a class="btn-reset" href="{{ route('executive.page', ['page' => 'JadwalPesanan']) }}">Reset</a>
            </div>
        </form>
    </div>

    {{-- Booking Cards --}}
    <div class="booking-grid">
        @forelse($bookings as $booking)
            @php
                $jamMulai   = \Carbon\Carbon::parse($booking->start_time)->format('H:i');
                $jamSelesai = \Carbon\Carbon::parse($booking->end_time)->format('H:i');

                // Addon IDs untuk prefill edit (pastikan controller eager load addons)
                $addonIds = [];
                if (isset($booking->addons) && $booking->addons instanceof \Illuminate\Support\Collection) {
                    $addonIds = $booking->addons->pluck('id')->values();
                } elseif (!empty($booking->addons_json)) {
                    $addonIds = collect(json_decode($booking->addons_json, true) ?: []);
                }
            @endphp

            <div class="booking-card status-{{ $booking->status }}">
                <div class="order-code">{{ $booking->kode_pesanan }}</div>

                <div class="booking-status badge-{{ $booking->status }}">
                    {{ ucfirst($booking->status) }}
                </div>

                <div class="booking-meta">
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->photoshoot_date)->format('d M Y') }}</p>
                    <p><strong>Jam:</strong> {{ $jamMulai }} - {{ $jamSelesai }}</p>
                    <p><strong>Paket:</strong> {{ $booking->package?->name ?? '-' }}</p>
                    <p><strong>Style:</strong> {{ $booking->style ?? '-' }}</p>
                </div>

                <div class="booking-info">
                    <div class="member-box">
                        <div class="member-title">CPP</div>
                        <div class="member-details">
                            <div class="member-name">{{ $booking->nama_cpp }}</div>
                            <div class="member-role">{{ $booking->phone_cpp }}</div>
                        </div>
                    </div>

                    <div class="member-box">
                        <div class="member-title">CPW</div>
                        <div class="member-details">
                            <div class="member-name">{{ $booking->nama_cpw }}</div>
                            <div class="member-role">{{ $booking->phone_cpw }}</div>
                        </div>
                    </div>
                </div>

                <div class="order-actions">
                    {{-- Edit booking (wizard modal) --}}
                    <button type="button"
                            class="action-btn btn-edit js-open-booking-modal"
                            data-mode="edit"
                            data-id="{{ $booking->id }}"

                            data-status="{{ $booking->status ?? 'submitted' }}"

                            data-nama_cpp="{{ $booking->nama_cpp }}"
                            data-email_cpp="{{ $booking->email_cpp }}"
                            data-phone_cpp="{{ $booking->phone_cpp }}"
                            data-alamat_cpp="{{ $booking->alamat_cpp }}"

                            data-nama_cpw="{{ $booking->nama_cpw }}"
                            data-email_cpw="{{ $booking->email_cpw }}"
                            data-phone_cpw="{{ $booking->phone_cpw }}"
                            data-alamat_cpw="{{ $booking->alamat_cpw }}"

                            data-ig_cpp="{{ $booking->ig_cpp }}"
                            data-ig_cpw="{{ $booking->ig_cpw }}"
                            data-tiktok_cpp="{{ $booking->tiktok_cpp }}"
                            data-tiktok_cpw="{{ $booking->tiktok_cpw }}"
                            data-sosmed_lain="{{ $booking->sosmed_lain }}"

                            data-package_id="{{ $booking->package_id }}"
                            data-date="{{ $booking->photoshoot_date }}"
                            data-style="{{ $booking->style }}"

                            data-slot_code="{{ $booking->slot_code }}"
                            data-photoshoot_slot="{{ $booking->photoshoot_slot }}"
                            data-start="{{ $jamMulai }}"
                            data-end="{{ $jamSelesai }}"

                            data-extra_slot_code="{{ $booking->extra_slot_code }}"
                            data-extra_photoshoot_slot="{{ $booking->extra_photoshoot_slot }}"
                            data-extra_start_time="{{ $booking->extra_start_time }}"
                            data-extra_end_time="{{ $booking->extra_end_time }}"
                            data-extra_minutes="{{ $booking->extra_minutes }}"

                            data-tema_id="{{ $booking->tema_id }}"
                            data-tema_nama="{{ $booking->tema_nama }}"
                            data-tema_kode="{{ $booking->tema_kode }}"

                            data-tema2_id="{{ $booking->tema2_id }}"
                            data-tema2_nama="{{ $booking->tema2_nama }}"
                            data-tema2_kode="{{ $booking->tema2_kode }}"

                            data-wedding_date="{{ $booking->wedding_date }}"
                            data-notes="{{ $booking->notes }}"

                            data-addons='@json($addonIds)'>
                        <i class="fas fa-edit"></i>
                    </button>

                    {{-- Detail booking (modal detail kamu yang sudah ada) --}}
                    <button type="button"
                            class="action-btn btn-view js-open-booking-detail"
                            data-id="{{ $booking->id }}"
                            data-kode="{{ $booking->kode_pesanan }}"
                            data-status="{{ $booking->status }}"
                            data-tanggal="{{ $booking->photoshoot_date }}"
                            data-jam="{{ $jamMulai }} - {{ $jamSelesai }}"
                            data-created_at="{{ $booking->created_at }}"
                            data-nama_cpp="{{ $booking->nama_cpp }}"
                            data-email_cpp="{{ $booking->email_cpp }}"
                            data-phone_cpp="{{ $booking->phone_cpp }}"
                            data-alamat_cpp="{{ $booking->alamat_cpp }}"
                            data-nama_cpw="{{ $booking->nama_cpw }}"
                            data-email_cpw="{{ $booking->email_cpw }}"
                            data-phone_cpw="{{ $booking->phone_cpw }}"
                            data-alamat_cpw="{{ $booking->alamat_cpw }}"
                            data-ig_cpp="{{ $booking->ig_cpp }}"
                            data-ig_cpw="{{ $booking->ig_cpw }}"
                            data-tiktok_cpp="{{ $booking->tiktok_cpp }}"
                            data-tiktok_cpw="{{ $booking->tiktok_cpw }}"
                            data-sosmed_lain="{{ $booking->sosmed_lain }}"
                            data-package="{{ $booking->package?->name }}"
                            data-package_price="{{ $booking->package_price }}"
                            data-addons_total="{{ $booking->addons_total }}"
                            data-grand_total="{{ $booking->grand_total }}"
                            data-slot_code="{{ $booking->slot_code }}"
                            data-photoshoot_slot="{{ $booking->photoshoot_slot }}"
                            data-extra_slot_code="{{ $booking->extra_slot_code }}"
                            data-extra_photoshoot_slot="{{ $booking->extra_photoshoot_slot }}"
                            data-extra_start_time="{{ $booking->extra_start_time }}"
                            data-extra_end_time="{{ $booking->extra_end_time }}"
                            data-extra_minutes="{{ $booking->extra_minutes }}"
                            data-tema_nama="{{ $booking->tema_nama }}"
                            data-tema_kode="{{ $booking->tema_kode }}"
                            data-tema2_nama="{{ $booking->tema2_nama }}"
                            data-tema2_kode="{{ $booking->tema2_kode }}"
                            data-style="{{ $booking->style }}"
                            data-wedding_date="{{ $booking->wedding_date }}"
                            data-notes="{{ $booking->notes }}"
                            data-nama_gabungan="{{ $booking->nama_gabungan }}"
                            data-email_gabungan="{{ $booking->email_gabungan }}"
                            data-phone_gabungan="{{ $booking->phone_gabungan }}">
                        <i class="fas fa-eye"></i>
                    </button>

                    {{-- Hapus booking --}}
                    <form action="{{ route('executive.homepages.destroy', ['section' => 'bookingexecutive', 'id' => $booking->id]) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus booking ini?')"
                          style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

        @empty
            <div style="padding:16px;opacity:.7">Belum ada booking untuk filter yang dipilih.</div>
        @endforelse
    </div>

    {{-- Floating button --}}
    <button type="button" class="floating-btn js-open-booking-modal" data-mode="create">
        + Booking Baru
    </button>
</div>

{{-- ===================== MODAL WIZARD (CREATE + EDIT) ===================== --}}
<div class="booking-modal-backdrop" id="bookingModal" aria-hidden="true">
    <div class="booking-modal" role="dialog" aria-modal="true">
        <div class="booking-modal-header">
            <h3 id="bookingModalTitle">Booking Baru</h3>
            <button class="booking-modal-close" type="button" data-close>&times;</button>
        </div>

        <div class="booking-modal-body" id="bookingModalBody">
            <form id="bookingForm"
                  method="POST"
                  action="{{ route('executive.homepages.store', ['section' => 'bookingexecutive']) }}"
                  data-store-url="{{ route('executive.homepages.store', ['section' => 'bookingexecutive']) }}"
                  data-update-template="{{ route('executive.homepages.update', ['section' => 'bookingexecutive', 'id' => '__ID__']) }}"
                  data-default-date="{{ $selectedDate ?? now()->toDateString() }}">

                @csrf
                <div id="methodBag"></div>

                <div id="bookingWizard">
                    {{-- Stepper --}}
                    <div class="wizard-stepper">
                        <div class="wiz-step is-active" data-step="1"><span>1</span><p>Identitas</p></div>
                        <div class="wiz-step" data-step="2"><span>2</span><p>Booking</p></div>
                        <div class="wiz-step" data-step="3"><span>3</span><p>Sosmed</p></div>
                        <div class="wiz-step" data-step="4"><span>4</span><p>Review</p></div>
                    </div>

                    {{-- STEP 1 --}}
                    <section class="wiz-panel is-active" data-panel="1">
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Nama CPP *</label>
                                <input id="nama_cpp" type="text" placeholder="Nama CPP">
                            </div>
                            <div class="form-group">
                                <label>No HP CPP *</label>
                                <input id="phone_cpp" type="text" placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="form-group">
                                <label>Email CPP</label>
                                <input id="email_cpp" type="email" placeholder="email@contoh.com">
                            </div>
                            <div class="form-group">
                                <label>Alamat CPP</label>
                                <input id="alamat_cpp" type="text" placeholder="Alamat">
                            </div>

                            <div class="form-group">
                                <label>Nama CPW *</label>
                                <input id="nama_cpw" type="text" placeholder="Nama CPW">
                            </div>
                            <div class="form-group">
                                <label>No HP CPW *</label>
                                <input id="phone_cpw" type="text" placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="form-group">
                                <label>Email CPW</label>
                                <input id="email_cpw" type="email" placeholder="email@contoh.com">
                            </div>
                            <div class="form-group">
                                <label>Alamat CPW</label>
                                <input id="alamat_cpw" type="text" placeholder="Alamat">
                            </div>
                        </div>
                    </section>

                    {{-- STEP 2 --}}
                    <section class="wiz-panel" data-panel="2">
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Paket *</label>
                                <select id="package_id">
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach($packages as $p)
                                        <option value="{{ $p->id }}"
                                                data-durasi="{{ $p->durasi ?? 0 }}"
                                                data-final_price="{{ $p->final_price ?? 0 }}">
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Photoshoot *</label>
                                <input id="photoshoot_date" type="date" value="{{ $selectedDate ?? now()->toDateString() }}">
                            </div>

                            <div class="form-group">
                                <label>Style *</label>
                                <select id="style">
                                    <option value="">-- Pilih Style --</option>
                                    <option value="Hijab">Hijab</option>
                                    <option value="HairDo">HairDo</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Wedding Date</label>
                                <input id="wedding_date" type="date">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:14px;">
                            <label>Slot Utama *</label>
                            <div id="slotList" class="slots-grid">
                                <p style="opacity:.7">Pilih paket & tanggal terlebih dahulu.</p>
                            </div>
                        </div>

                        <div class="grid-2" style="margin-top:14px;">
                            <div class="form-group">
                                <label>Tema Utama (Nama)</label>
                                <select id="tema_nama">
                                    <option value="">-- Pilih Nama Tema --</option>
                                    @foreach($temas as $t)
                                        <option value="{{ $t->nama }}">{{ $t->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tema Utama (Kode)</label>
                                <select id="tema_kode" disabled>
                                    <option value="">-- Pilih Kode --</option>
                                    @foreach($temas as $t)
                                        <option value="{{ $t->kode }}">{{ $t->kode }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="tema_id">
                            </div>
                        </div>

                        {{-- Addons --}}
                        @php
                            $addonGroups = collect($addons ?? [])->groupBy('kategori');
                        @endphp

                        <div class="form-group" style="margin-top:14px;">
                            <label>Addon</label>

                            @if($addonGroups->isNotEmpty())
                                <div class="addons-grid">
                                    @foreach ($addonGroups as $kategori => $group)
                                        <div class="addon-group addon-kat-{{ $kategori }}">
                                            <h4>{{ $group->first()->kategori_label ?? ('Kategori '.$kategori) }}</h4>

                                            @foreach ($group as $addon)
                                                <label class="addon-item">
                                                    <input type="checkbox"
                                                           class="addon-check"
                                                           data-id="{{ $addon->id }}"
                                                           data-kategori="{{ $addon->kategori }}"
                                                           data-harga="{{ $addon->harga }}"
                                                           data-durasi="{{ $addon->durasi ?? 0 }}">
                                                    <span class="addon-name">{{ $addon->nama }}</span>
                                                    <small>{{ $addon->durasi_label ?? '' }}</small>
                                                    <span>Rp {{ number_format($addon->harga, 0, ',', '.') }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p style="opacity:.7">Belum ada addon aktif.</p>
                            @endif
                        </div>

                        {{-- Extra slot addon kategori 1 --}}
                        <div id="extraSlotWrapper" style="display:none;margin-top:16px">
                            <h4>Slot Tambahan (Addon Slot Waktu)</h4>
                            <div id="extraSlotList" class="slots-grid">
                                <p style="opacity:.7">Pilih addon slot & slot utama terlebih dahulu.</p>
                            </div>
                        </div>

                        {{-- Extra tema addon kategori 2 --}}
                        <div id="extraTemaWrapper" style="display:none;margin-top:16px">
                            <h4>Tema Tambahan (Addon Tema)</h4>
                            <div class="grid-2">
                                <div class="form-group">
                                    <label>Tema Tambahan (Nama)</label>
                                    <select id="tema2_nama">
                                        <option value="">-- Pilih Nama Tema --</option>
                                        @foreach($temas as $t)
                                            <option value="{{ $t->nama }}">{{ $t->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Tema Tambahan (Kode)</label>
                                    <select id="tema2_kode" disabled>
                                        <option value="">-- Pilih Kode --</option>
                                        @foreach($temas as $t)
                                            <option value="{{ $t->kode }}">{{ $t->kode }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="tema2_id">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:14px;">
                            <label>Catatan</label>
                            <textarea id="notes" rows="3" placeholder="Catatan internal / permintaan klien"></textarea>
                        </div>
                    </section>

                    {{-- STEP 3 --}}
                    <section class="wiz-panel" data-panel="3">
                        <div class="grid-2">
                            <div class="form-group">
                                <label>IG CPP</label>
                                <input id="ig_cpp" type="text" placeholder="@username">
                            </div>
                            <div class="form-group">
                                <label>TikTok CPP</label>
                                <input id="tiktok_cpp" type="text" placeholder="@username">
                            </div>

                            <div class="form-group">
                                <label>IG CPW</label>
                                <input id="ig_cpw" type="text" placeholder="@username">
                            </div>
                            <div class="form-group">
                                <label>TikTok CPW</label>
                                <input id="tiktok_cpw" type="text" placeholder="@username">
                            </div>
                        </div>
                    </section>

                    {{-- STEP 4 --}}
                    <section class="wiz-panel" data-panel="4">
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Status *</label>
                                <select id="status">
                                    <option value="submitted">submitted</option>
                                    <option value="confirmed">confirmed</option>
                                    <option value="cancelled">cancelled</option>
                                    <option value="completed">completed</option>
                                </select>
                            </div>
                        </div>

                        <div id="summaryBox" class="summary-box">
                            <p style="opacity:.7">Ringkasan akan muncul di sini.</p>
                        </div>

                        <div id="hiddenBag"></div>
                    </section>

                    {{-- Footer --}}
                    <div class="wizard-footer">
                        <button type="button" class="btn-secondary" id="btnPrev">Kembali</button>
                        <button type="button" class="btn-primary" id="btnNext">Lanjut</button>
                        <button type="submit" class="btn-primary" id="btnSubmit" style="display:none;">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
