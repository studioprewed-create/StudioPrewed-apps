@section('content')
@php
  $selectedDate = $selectedDate ?? now()->toDateString();
  $bookings     = $bookings ?? collect();
  $packages     = $packages ?? collect();
  $addons       = $addons ?? collect();
  $temas        = $temas ?? collect();

  $qStatus  = request('status', 'all');
  $qSearch  = request('search', '');
  $qPackage = request('package_id', '');
@endphp

<div class="jp-page" data-page="JadwalPesanan">
  <div class="jp-header">
    <div>
      <div class="jp-title">Jadwal Pesanan</div>
      <div class="jp-subtitle">Kelola booking (Admin/Executive). Pilih tanggal dari kalender, cek slot, lalu buat/edit booking.</div>
    </div>

    <div class="jp-header-actions">
      <button type="button" class="jp-btn jp-btn-primary" id="jpNewBookingBtn">Booking Baru</button>
    </div>
  </div>

  {{-- FILTER FORM (kalender + slot preview + filter universal) --}}
  <form id="jpFilterForm" class="jp-filter-wrap" method="GET" action="{{ url()->current() }}">
    <input type="hidden" name="date" id="filterDate" value="{{ $selectedDate }}"/>

    <div class="jp-scheduler">
      {{-- LEFT: Calendar box --}}
      <div class="jp-card jp-calendar-card">
        <div class="jp-cal-head">
          <button type="button" class="jp-icon-btn" id="jpCalPrev" aria-label="Prev month">‹</button>
          <div class="jp-cal-label" id="jpCalLabel">—</div>
          <button type="button" class="jp-icon-btn" id="jpCalNext" aria-label="Next month">›</button>
        </div>

        <div class="jp-cal-week">
          <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
        </div>

        <div class="jp-cal-grid" id="jpCalGrid"></div>

        <div class="jp-cal-footer">
          <div class="jp-pill">
            <span class="jp-pill-label">Tanggal dipilih</span>
            <span class="jp-pill-value" id="jpSelectedDateLabel">{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</span>
          </div>
          <button type="button" class="jp-btn jp-btn-ghost" id="jpTodayBtn">Hari ini</button>
        </div>
      </div>

      {{-- RIGHT: Slot preview --}}
      <div class="jp-card jp-slots-card">
        <div class="jp-slots-head">
          <div>
            <div class="jp-card-title">Slot Preview (2 Studio)</div>
            <div class="jp-card-subtitle">Preview di bawah mengikuti tanggal dari kalender. Pilih paket untuk memuat slot.</div>
          </div>

          <div class="jp-slot-controls">
            <label class="jp-label">Paket</label>
            <select name="package_id" id="jpPackageFilter" class="jp-select">
              <option value="">— Semua Paket —</option>
              @foreach($packages as $p)
                <option value="{{ $p->id }}" @selected((string)$qPackage === (string)$p->id)>
                  {{ $p->nama_paket ?? $p->name ?? ('Paket #'.$p->id) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="jp-slots-meta">
          <div class="jp-meta-item">
            <div class="jp-meta-k">Tanggal</div>
            <div class="jp-meta-v" id="jpSlotsDateLabel">{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</div>
          </div>
          <div class="jp-meta-item">
            <div class="jp-meta-k">Kapasitas</div>
            <div class="jp-meta-v">2 booking per slot (Studio 1 & Studio 2)</div>
          </div>
        </div>

        <div id="jpSlotsGrid" class="jp-slots-grid">
          <div class="jp-slots-empty">Pilih paket untuk melihat slot.</div>
        </div>

        <div class="jp-slots-legend">
          <span class="jp-legend"><span class="jp-dot jp-dot-ok"></span> Tersedia</span>
          <span class="jp-legend"><span class="jp-dot jp-dot-full"></span> Penuh</span>
          <span class="jp-legend-note">Catatan: karena kamu tidak mau ubah API, tampilan “Studio 1/2” bersifat indikator konsep. Ketersediaan mengikuti flag `available` dari API.</span>
        </div>
      </div>
    </div>

    {{-- Universal filters --}}
    <div class="jp-card jp-universal-filter">
      <div class="jp-filter-row">
        <div class="jp-field">
          <label class="jp-label">Status</label>
          <select name="status" class="jp-select">
            <option value="all" @selected($qStatus==='all')>Semua</option>
            <option value="pending" @selected($qStatus==='pending')>Submitted</option>
            <option value="confirmed" @selected($qStatus==='confirmed')>Confirmed</option>
            <option value="canceled" @selected($qStatus==='canceled')>Cancelled</option>
            <option value="completed" @selected($qStatus==='completed')>Completed</option>
          </select>
        </div>

        <div class="jp-field jp-field-grow">
          <label class="jp-label">Cari</label>
          <input type="text" name="search" value="{{ $qSearch }}" class="jp-input" placeholder="Kode, nama, phone...">
        </div>

        <div class="jp-filter-actions">
          <button type="submit" class="jp-btn jp-btn-primary">Terapkan</button>
          <a class="jp-btn jp-btn-ghost" href="{{ url()->current() }}">Reset</a>
        </div>
      </div>
    </div>
  </form>

  {{-- LIST BOOKING --}}
  <div class="jp-list-head">
    <div class="jp-list-title">Daftar Booking ({{ $bookings->count() }})</div>
    <div class="jp-list-sub">Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</div>
  </div>

  <div class="jp-cards">
    @forelse($bookings as $b)
      @php
        $status = $b->status ?? 'submitted';
        $badgeClass = match($status){
          'confirmed' => 'jp-badge jp-badge-ok',
          'completed' => 'jp-badge jp-badge-done',
          'cancelled' => 'jp-badge jp-badge-full',
          default     => 'jp-badge jp-badge-warn',
        };

        $pkgName = $b->package->nama_paket ?? $b->package->name ?? ($b->package_id ? 'Paket #'.$b->package_id : '-');

        $bookingJson = [
          'id' => $b->id,
          'nama_cpp' => $b->nama_cpp,
          'phone_cpp' => $b->phone_cpp,
          'email_cpp' => $b->email_cpp,
          'alamat_cpp' => $b->alamat_cpp,

          'nama_cpw' => $b->nama_cpw,
          'phone_cpw' => $b->phone_cpw,
          'email_cpw' => $b->email_cpw,
          'alamat_cpw' => $b->alamat_cpw,

          'package_id' => $b->package_id,
          'photoshoot_date' => $b->photoshoot_date,
          'style' => $b->style,
          'wedding_date' => $b->wedding_date,

          'slot_code' => $b->slot_code,
          'photoshoot_slot' => $b->photoshoot_slot,
          'start_time' => $b->start_time,
          'end_time' => $b->end_time,

          'tema_id' => $b->tema_id,
          'tema_nama' => $b->tema_nama,
          'tema_kode' => $b->tema_kode,

          'tema2_id' => $b->tema2_id,
          'tema2_nama' => $b->tema2_nama,
          'tema2_kode' => $b->tema2_kode,

          'notes' => $b->notes,
          'ig_cpp' => $b->ig_cpp,
          'tiktok_cpp' => $b->tiktok_cpp,
          'ig_cpw' => $b->ig_cpw,
          'tiktok_cpw' => $b->tiktok_cpw,

          'status' => $b->status,

          'extra_slot_code' => $b->extra_slot_code,
          'extra_photoshoot_slot' => $b->extra_photoshoot_slot,
          'extra_start_time' => $b->extra_start_time,
          'extra_end_time' => $b->extra_end_time,
          'extra_minutes' => $b->extra_minutes,

          // addons: kalau relasi ada, isi array ids; kalau tidak, biarkan []
          'addons' => method_exists($b, 'addons') && $b->relationLoaded('addons')
              ? $b->addons->pluck('id')->values()->all()
              : [],
        ];
      @endphp

      <div class="jp-card jp-booking-card"
           data-booking='@json($bookingJson)'>
        <div class="jp-booking-top">
          <div class="jp-booking-time">
            <div class="jp-time-main">{{ substr((string)$b->start_time,0,5) }}–{{ substr((string)$b->end_time,0,5) }}</div>
            <div class="jp-time-sub">{{ $b->photoshoot_slot ?? '-' }} • {{ $b->slot_code ?? '-' }}</div>
          </div>
          <div class="{{ $badgeClass }}">{{ strtoupper($status) }}</div>
        </div>

        <div class="jp-booking-mid">
          <div class="jp-booking-name">{{ $b->nama_cpp ?? '-' }} & {{ $b->nama_cpw ?? '-' }}</div>
          <div class="jp-booking-meta">
            <div><span class="jp-k">Paket:</span> {{ $pkgName }}</div>
            <div><span class="jp-k">Style:</span> {{ $b->style ?? '-' }}</div>
            <div><span class="jp-k">Phone:</span> {{ $b->phone_cpp ?? '-' }} / {{ $b->phone_cpw ?? '-' }}</div>
          </div>
        </div>

        <div class="jp-booking-actions">
          <button type="button" class="jp-btn jp-btn-ghost jpEditBtn">Edit</button>

          <form method="POST"
                action="{{ route('executive.homepages.destroy', ['section' => 'bookingexecutive', 'id' => $b->id]) }}"
                onsubmit="return confirm('Hapus booking ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="jp-btn jp-btn-danger">Hapus</button>
          </form>
        </div>
      </div>
    @empty
      <div class="jp-empty">
        Belum ada booking pada tanggal ini.
      </div>
    @endforelse
  </div>

  {{-- ================= MODAL WIZARD ================= --}}
  <div class="jp-modal" id="jpBookingModal" aria-hidden="true">
    <div class="jp-modal-backdrop" id="jpModalBackdrop"></div>

    <div class="jp-modal-card" role="dialog" aria-modal="true" aria-labelledby="jpModalTitle">
      <div class="jp-modal-head">
        <div class="jp-modal-title" id="jpModalTitle">Booking Baru</div>
        <button type="button" class="jp-icon-btn" id="jpModalClose" aria-label="Close">✕</button>
      </div>

      <div class="jp-stepper">
        <button type="button" class="jp-step is-active" data-step="1">1<br><span>Identitas</span></button>
        <button type="button" class="jp-step" data-step="2">2<br><span>Booking</span></button>
        <button type="button" class="jp-step" data-step="3">3<br><span>Sosmed</span></button>
        <button type="button" class="jp-step" data-step="4">4<br><span>Review</span></button>
      </div>

      <form id="jpBookingForm"
            method="POST"
            action="{{ route('executive.homepages.store', ['section' => 'bookingexecutive']) }}"
            data-store-url="{{ route('executive.homepages.store', ['section' => 'bookingexecutive']) }}"
            data-update-url-template="{{ route('executive.homepages.update', ['section' => 'bookingexecutive', 'id' => 0]) }}"
            data-api-slots="{{ url()->to('/executive/api/slots') }}">
        @csrf
        <input type="hidden" name="_method" id="jpMethod" value="POST">
        <input type="hidden" name="id" id="jpBookingId" value="">

        {{-- hidden fields derived from slot select --}}
        <input type="hidden" name="photoshoot_slot" id="f_photoshoot_slot">
        <input type="hidden" name="start_time" id="f_start_time">
        <input type="hidden" name="end_time" id="f_end_time">

        <input type="hidden" name="extra_photoshoot_slot" id="f_extra_photoshoot_slot">
        <input type="hidden" name="extra_start_time" id="f_extra_start_time">
        <input type="hidden" name="extra_end_time" id="f_extra_end_time">
        <input type="hidden" name="extra_minutes" id="f_extra_minutes">

        <div class="jp-modal-body">
          {{-- STEP 1 --}}
          <div class="jp-step-panel is-active" data-step="1">
            <div class="jp-grid-2">
              <div class="jp-field">
                <label class="jp-label">Nama CPP *</label>
                <input class="jp-input" name="nama_cpp" id="f_nama_cpp" required>
              </div>
              <div class="jp-field">
                <label class="jp-label">No HP CPP *</label>
                <input class="jp-input" name="phone_cpp" id="f_phone_cpp" required placeholder="08xxxxxxxxxx">
              </div>

              <div class="jp-field">
                <label class="jp-label">Email CPP</label>
                <input class="jp-input" name="email_cpp" id="f_email_cpp" type="email" placeholder="email@contoh.com">
              </div>
              <div class="jp-field">
                <label class="jp-label">Alamat CPP</label>
                <input class="jp-input" name="alamat_cpp" id="f_alamat_cpp">
              </div>

              <div class="jp-field">
                <label class="jp-label">Nama CPW *</label>
                <input class="jp-input" name="nama_cpw" id="f_nama_cpw" required>
              </div>
              <div class="jp-field">
                <label class="jp-label">No HP CPW *</label>
                <input class="jp-input" name="phone_cpw" id="f_phone_cpw" required placeholder="08xxxxxxxxxx">
              </div>

              <div class="jp-field">
                <label class="jp-label">Email CPW</label>
                <input class="jp-input" name="email_cpw" id="f_email_cpw" type="email" placeholder="email@contoh.com">
              </div>
              <div class="jp-field">
                <label class="jp-label">Alamat CPW</label>
                <input class="jp-input" name="alamat_cpw" id="f_alamat_cpw">
              </div>
            </div>
          </div>

          {{-- STEP 2 --}}
          <div class="jp-step-panel" data-step="2">
            <div class="jp-grid-2">
              <div class="jp-field">
                <label class="jp-label">Paket *</label>
                <select class="jp-select" name="package_id" id="f_package_id" required>
                  <option value="">— Pilih Paket —</option>
                  @foreach($packages as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_paket ?? $p->name ?? ('Paket #'.$p->id) }}</option>
                  @endforeach
                </select>
              </div>

              <div class="jp-field">
                <label class="jp-label">Tanggal Photoshoot *</label>
                <input class="jp-input" type="date" name="photoshoot_date" id="f_photoshoot_date" required>
              </div>

              <div class="jp-field">
                <label class="jp-label">Style *</label>
                <select class="jp-select" name="style" id="f_style" required>
                  <option value="">— Pilih Style —</option>
                  <option value="Hijab">Hijab</option>
                  <option value="HairDo">HairDo</option>
                </select>
              </div>

              <div class="jp-field">
                <label class="jp-label">Wedding Date</label>
                <input class="jp-input" type="date" name="wedding_date" id="f_wedding_date">
              </div>

              <div class="jp-field jp-field-full">
                <label class="jp-label">Slot Utama *</label>
                <select class="jp-select" name="slot_code" id="f_slot_code" required>
                  <option value="">Pilih paket & tanggal terlebih dahulu.</option>
                </select>
                <div class="jp-help" id="jpSlotHint">Studio 1 & Studio 2 mengikuti kapasitas API.</div>
              </div>

              <div class="jp-field">
                <label class="jp-label">Tema Utama (Nama)</label>
                <select class="jp-select" name="tema_nama" id="f_tema_nama">
                  <option value="">— Pilih Nama Tema —</option>
                  @foreach($temas as $t)
                    <option value="{{ $t->nama }}" data-kode="{{ $t->kode ?? '' }}">{{ $t->nama }}</option>
                  @endforeach
                </select>
              </div>

              <div class="jp-field">
                <label class="jp-label">Tema Utama (Kode)</label>
                <select class="jp-select" name="tema_kode" id="f_tema_kode">
                  <option value="">— Pilih Kode —</option>
                  @foreach($temas as $t)
                    <option value="{{ $t->kode ?? '' }}" data-nama="{{ $t->nama }}">{{ $t->kode ?? '-' }}</option>
                  @endforeach
                </select>
              </div>

              <div class="jp-field">
                <label class="jp-label">Tema Tambahan (Nama)</label>
                <select class="jp-select" name="tema2_nama" id="f_tema2_nama">
                  <option value="">— Pilih Nama Tema —</option>
                  @foreach($temas as $t)
                    <option value="{{ $t->nama }}" data-kode="{{ $t->kode ?? '' }}">{{ $t->nama }}</option>
                  @endforeach
                </select>
              </div>

              <div class="jp-field">
                <label class="jp-label">Tema Tambahan (Kode)</label>
                <select class="jp-select" name="tema2_kode" id="f_tema2_kode">
                  <option value="">— Pilih Kode —</option>
                  @foreach($temas as $t)
                    <option value="{{ $t->kode ?? '' }}" data-nama="{{ $t->nama }}">{{ $t->kode ?? '-' }}</option>
                  @endforeach
                </select>
              </div>

              <div class="jp-field jp-field-full">
                <div class="jp-addon-wrap">
                  <div class="jp-addon-title">Addon / Fitur Tambahan</div>

                  @php $groups = $addons->groupBy('kategori'); @endphp
                  @foreach($groups as $kategori => $items)
                    <div class="jp-addon-group">
                      <div class="jp-addon-group-title">{{ $kategori ?: 'Lainnya' }}</div>
                      <div class="jp-addon-grid">
                        @foreach($items as $a)
                          <label class="jp-addon-item">
                            <input type="checkbox"
                                   name="addons[]"
                                   value="{{ $a->id }}"
                                   class="jp-addon-checkbox"
                                   data-name="{{ $a->nama ?? $a->name ?? '' }}">
                            <span class="jp-addon-name">{{ $a->nama ?? $a->name ?? ('Addon #'.$a->id) }}</span>
                            <span class="jp-addon-price">
                              Rp {{ number_format((int)($a->harga ?? 0), 0, ',', '.') }}
                            </span>
                          </label>
                        @endforeach
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="jp-field jp-field-full">
                <label class="jp-label">Catatan</label>
                <textarea class="jp-textarea" name="notes" id="f_notes" rows="3" placeholder="Catatan internal / permintaan klien"></textarea>
              </div>

              <div class="jp-field jp-field-full">
                <label class="jp-label">Status *</label>
                <select class="jp-select" name="status" id="f_status" required>
                  <option value="submitted">submitted</option>
                  <option value="confirmed">confirmed</option>
                  <option value="cancelled">cancelled</option>
                  <option value="completed">completed</option>
                </select>
              </div>

            </div>
          </div>

          {{-- STEP 3 --}}
          <div class="jp-step-panel" data-step="3">
            <div class="jp-grid-2">
              <div class="jp-field">
                <label class="jp-label">IG CPP</label>
                <input class="jp-input" name="ig_cpp" id="f_ig_cpp" placeholder="@username">
              </div>
              <div class="jp-field">
                <label class="jp-label">TikTok CPP</label>
                <input class="jp-input" name="tiktok_cpp" id="f_tiktok_cpp" placeholder="@username">
              </div>

              <div class="jp-field">
                <label class="jp-label">IG CPW</label>
                <input class="jp-input" name="ig_cpw" id="f_ig_cpw" placeholder="@username">
              </div>
              <div class="jp-field">
                <label class="jp-label">TikTok CPW</label>
                <input class="jp-input" name="tiktok_cpw" id="f_tiktok_cpw" placeholder="@username">
              </div>
            </div>
          </div>

          {{-- STEP 4 --}}
          <div class="jp-step-panel" data-step="4">
            <div class="jp-review">
              <div class="jp-review-title">Ringkasan</div>
              <div class="jp-review-box" id="jpReviewBox">Ringkasan akan muncul di sini.</div>
            </div>
          </div>
        </div>

        <div class="jp-modal-foot">
          <button type="button" class="jp-btn jp-btn-ghost" id="jpPrevBtn">Kembali</button>
          <button type="button" class="jp-btn jp-btn-primary" id="jpNextBtn">Lanjut</button>
          <button type="submit" class="jp-btn jp-btn-primary" id="jpSubmitBtn" style="display:none;">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>