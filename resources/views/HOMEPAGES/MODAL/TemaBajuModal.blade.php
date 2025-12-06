{{-- resources/views/HOMEPAGES/FITUR/Modal/tema-modal.blade.php --}}
@php
  // Reindex biar bisa ambil prev/next by index
  $temasArr = $temas->values();
  $count    = $temasArr->count();
@endphp

@foreach($temasArr as $i => $t)
  @php
    $prevIdx = ($i - 1 + $count) % $count;
    $nextIdx = ($i + 1) % $count;
    $prevId  = $temasArr[$prevIdx]->id;
    $nextId  = $temasArr[$nextIdx]->id;

    $imgs    = $t->images ? json_decode($t->images, true) : [];
    $imgUrls = collect($imgs)->map(fn($p)=>asset('public/storage/'.$p))->all();
    if (empty($imgUrls)) { $imgUrls = [asset('asset/IMGhome/bg1.jpg')]; }
  @endphp

  <div class="pkg-modal" id="temaModal-{{ $t->id }}" role="dialog" aria-hidden="true">
    <div class="pkg-modal__backdrop" data-close-detail></div>

    <div class="pkg-modal__panel" role="document" aria-label="Detail tema {{ $t->nama }}">
      <button class="pkg-modal__close" type="button" aria-label="Tutup" data-close-detail>&times;</button>

      {{-- PANAH NAVIGASI TEMA (Prev / Next) --}}
      <button type="button"
              class="nav-btn prev"
              data-tema-nav
              data-target="#temaModal-{{ $prevId }}"
              aria-label="Tema sebelumnya">
        <i class="fas fa-chevron-left"></i>
      </button>

      <button type="button"
              class="nav-btn next"
              data-tema-nav
              data-target="#temaModal-{{ $nextId }}"
              aria-label="Tema berikutnya">
        <i class="fas fa-chevron-right"></i>
      </button>
      {{-- /PANAH NAVIGASI TEMA --}}

      <div class="pkg-modal__body">
        {{-- KIRI: GALERI (gambar besar + thumbnails) --}}
        <div class="pkg-modal__media">
          <div class="tbm-gallery">
            <div class="tbm-main">
              <img id="tbmMain-{{ $t->id }}" src="{{ $imgUrls[0] }}" alt="Foto {{ $t->nama }}">
            </div>

            @if(count($imgUrls) > 1)
              <div class="tbm-thumbs" role="listbox" aria-label="Pilih foto {{ $t->nama }}">
                @foreach($imgUrls as $k => $src)
                  <button
                    type="button"
                    class="tbm-thumb {{ $k===0 ? 'is-active' : '' }}"
                    data-main="#tbmMain-{{ $t->id }}"
                    data-src="{{ $src }}"
                    aria-label="Foto {{ $k+1 }}"
                  >
                    <img src="{{ $src }}" alt="Thumbnail {{ $k+1 }}" loading="lazy">
                  </button>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        {{-- KANAN: DETAIL --}}
        <div class="pkg-modal__content">
          <h3 class="pkg-title">{{ $t->nama }}</h3>

          <div class="pkg-price">
            <span class="price-final">Rp {{ number_format($t->harga, 0, ',', '.') }}</span>
          </div>

          <div class="pkg-grid">
            <div class="pkg-item">
              <i class="fas fa-info-circle"></i>
              <div>
                <h5>Detail</h5>
                <p>{{ $t->detail }}</p>
              </div>
            </div>
            <div class="pkg-item">
              <i class="fas fa-palette"></i>
              <div>
                <h5>Designer</h5>
                <p>{{ $t->designer ?: '-' }}</p>
              </div>
            </div>
            <div class="pkg-item">
              <i class="fas fa-barcode"></i>
              <div>
                <h5>Kode Produk</h5>
                <p>{{ $t->kode ?: '-' }}</p>
              </div>
            </div>
            <div class="pkg-item">
              <i class="fas fa-ruler-combined"></i>
              <div>
                <h5>Ukuran</h5>
                <p>{{ $t->ukuran ?: '-' }}</p>
              </div>
            </div>
            <div class="pkg-item">
              <i class="fas fa-user-tie"></i>
              <div>
                <h5>Tipe</h5>
                <p>{{ $t->tipe ?: '-' }}</p>
              </div>
            </div>
          </div>

          <div class="pkg-actions">
            <button type="button" class="btn-ghost" data-close-detail>Tutup</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach
