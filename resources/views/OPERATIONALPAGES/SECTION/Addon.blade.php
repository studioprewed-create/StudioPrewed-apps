<details class="acc" {{ request()->hash === 'addon' ? 'open' : '' }}>
  <summary>
    <i class="fa-solid fa-puzzle-piece"></i> Addon (Extra Slot, Tema, Fitur Lain)
    <i class="fa-solid fa-chevron-right chev"></i>
  </summary>

  <div class="acc-body" id="addon">

    {{-- ================= FORM TAMBAH ADDON ================= --}}
    <form class="form-inline mb-3"
          action="{{ route('executive.homepages.store', ['section' => 'addon']) }}"
          method="POST">
      @csrf

      {{-- Nama addon --}}
      <input class="input" type="text" name="nama" placeholder="Nama addon" required>

      {{-- Kode opsional --}}
      <input class="input" type="text" name="kode" placeholder="Kode (opsional, unik)">

      {{-- Kategori --}}
      <select class="input" name="kategori" required>
        <option value="" disabled selected>Pilih kategori</option>
        <option value="1">Extra Slot Waktu</option>
        <option value="2">Tema Baju Tambahan</option>
        <option value="3">Fitur Lain (Frame / Cetak / dll)</option>
      </select>

      {{-- Durasi tambahan (dipakai kalau kategori = 1) --}}
      <select class="input" name="durasi">
        <option value="">Tanpa tambahan durasi</option>
        <option value="60">+ 1 jam (60 menit)</option>
        <option value="120">+ 2 jam (120 menit)</option>
      </select>

      {{-- Kapasitas slot tambahan (opsional) --}}
      <input class="input" type="number" name="kapasitas"
             placeholder="Kapasitas slot (opsional)"
             min="1">

      {{-- Harga --}}
      <input class="input" type="number" name="harga" placeholder="Harga (Rp)" min="0" required>

      {{-- Aktif / tidak --}}
      <label class="small-muted d-flex align-items-center gap-1 mb-0">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" checked> Aktif
      </label>

      {{-- Deskripsi singkat --}}
      <textarea class="input" name="deskripsi" rows="1"
                placeholder="Deskripsi singkat (opsional)"></textarea>

      <button class="btn btn-sm btn-primary" type="submit">Tambah Addon</button>
    </form>

    @php
      $labelKategori = [
        1 => 'Extra Slot Waktu',
        2 => 'Tema Baju Tambahan',
        3 => 'Fitur Lain (Frame / Cetak / dsb)',
      ];

      $groupedAddons = $addons->groupBy('kategori');
    @endphp

    {{-- ================= LIST ADDON PER KATEGORI ================= --}}
    @forelse($groupedAddons as $kategori => $list)
      <h6 class="mt-3 mb-2">
        {{ $labelKategori[$kategori] ?? ('Kategori '.$kategori) }}
      </h6>

      <div class="row g-3">
        @foreach($list as $a)
          @php
            $durasi    = $a->durasi;
            $kapasitas = $a->kapasitas;
          @endphp

          <div class="col-md-4">
            <div class="block-rel p-2 border rounded">

              {{-- Badge header --}}
              <div class="admin-badge d-flex justify-content-between align-items-center">
                <span>{{ $labelKategori[$a->kategori] ?? 'Addon' }} #{{ $a->id }}</span>
                <span class="badge {{ $a->is_active ? 'bg-success' : 'bg-secondary' }}">
                  {{ $a->is_active ? 'Aktif' : 'Non-aktif' }}
                </span>
              </div>

              <div class="small-muted mb-1 mt-1">Klik untuk edit teks:</div>

              {{-- Inline edit: nama --}}
              <div contenteditable="true"
                   class="contenteditable mb-1"
                   data-field="nama"
                   data-id="{{ $a->id }}"
                   data-section="addon">
                {{ $a->nama }}
              </div>

              {{-- Inline edit: deskripsi --}}
              <div contenteditable="true"
                   class="contenteditable mb-2"
                   data-field="deskripsi"
                   data-id="{{ $a->id }}"
                   data-section="addon">
                {{ $a->deskripsi }}
              </div>

              {{-- Info khusus kategori waktu --}}
              @if($a->kategori == 1)
                <div class="small-muted mb-1">
                  Durasi tambahan:
                  @if($durasi)
                    <strong>+ {{ $durasi }} menit</strong>
                  @else
                    <span class="text-danger">Belum diset</span>
                  @endif
                </div>
              @endif

              @if($kapasitas)
                <div class="small-muted mb-2">
                  Kapasitas slot: <strong>{{ $kapasitas }}</strong>
                </div>
              @endif

              <hr class="my-2">

              {{-- FORM UPDATE (durasi, kapasitas, harga, aktif) --}}
              <form class="form-inline d-flex align-items-center gap-2 mb-2"
                    action="{{ route('executive.homepages.update', ['section'=>'addon','id'=>$a->id]) }}"
                    method="POST">
                @csrf
                @method('PUT')

                @if($a->kategori == 1)
                  <select class="input form-control form-control-sm"
                          name="durasi"
                          style="width:150px;">
                    <option value="">Durasi default</option>
                    <option value="60"  {{ $durasi == 60  ? 'selected' : '' }}>+ 1 jam</option>
                    <option value="120" {{ $durasi == 120 ? 'selected' : '' }}>+ 2 jam</option>
                  </select>
                @endif

                <input class="input form-control form-control-sm"
                       type="number"
                       name="kapasitas"
                       value="{{ $kapasitas }}"
                       min="1"
                       placeholder="Kapasitas"
                       style="width:110px;">

                <input class="input form-control form-control-sm"
                       type="number"
                       name="harga"
                       value="{{ $a->harga }}"
                       min="0"
                       placeholder="Harga"
                       style="width:110px;">

                <label class="small-muted mb-0 d-flex align-items-center gap-1">
                  <input type="hidden" name="is_active" value="0">
                  <input type="checkbox" name="is_active" value="1" {{ $a->is_active ? 'checked' : '' }}>
                  Aktif
                </label>

                <button class="btn btn-sm btn-warning" type="submit">Simpan</button>
              </form>

              {{-- INFO KODE & KATEGORI --}}
              <div class="small text-muted mb-2">
                Kode: <strong>{{ $a->kode ?? '—' }}</strong><br>
                Kategori: {{ $labelKategori[$a->kategori] ?? $a->kategori }}
              </div>

              <hr class="my-1">

              {{-- HAPUS --}}
              <form action="{{ route('executive.homepages.destroy', ['section'=>'addon','id'=>$a->id]) }}"
                    method="POST"
                    onsubmit="return confirm('Hapus addon ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger w-100">Hapus</button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
    @empty
      <p class="small-muted">Belum ada addon terdaftar.</p>
    @endforelse
  </div>
</details>
