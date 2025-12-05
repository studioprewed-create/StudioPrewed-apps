{{-- resources/views/HOMEPAGES/FITUR/Modal/package-modal.blade.php --}}
@php
  // Reindex supaya bisa hitung prev/next by index
  $pkgs = $packages->values();
  $cnt  = $pkgs->count();
@endphp

@foreach($pkgs as $i => $pkg)
  @php
    $hasDisc = ($pkg->discount ?? 0) > 0;
    $final   = $pkg->final_price ?? $pkg->harga;
    $imgUrl  = $pkg->image_url ?? 'https://via.placeholder.com/600x400?text=No+Image';

    $prevIdx = ($i - 1 + $cnt) % $cnt;
    $nextIdx = ($i + 1) % $cnt;
    $prevId  = $pkgs[$prevIdx]->id;
    $nextId  = $pkgs[$nextIdx]->id;
  @endphp

  <div id="pkgModal-{{ $pkg->id }}" class="pkg-modal" aria-hidden="true">
    <div class="pkg-modal__backdrop" data-close-detail></div>
    
    <div class="pkg-modal__panel" role="dialog" aria-modal="true" aria-labelledby="pkgModalTitle-{{ $pkg->id }}">
      <button class="pkg-modal__close" type="button" data-close-detail aria-label="Tutup">&times;</button>

      {{-- PANAH PREV/NEXT (navigasi antar paket) --}}
      <button type="button"
              class="nav-btn prev"
              data-pkg-nav
              data-target="#pkgModal-{{ $prevId }}"
              aria-label="Paket sebelumnya">
        <i class="fas fa-chevron-left"></i>
      </button>

      <button type="button"
              class="nav-btn next"
              data-pkg-nav
              data-target="#pkgModal-{{ $nextId }}"
              aria-label="Paket berikutnya">
        <i class="fas fa-chevron-right"></i>
      </button>
      {{-- /PANAH PREV/NEXT --}}

      <div class="pkg-modal__body">
        {{-- Kiri: Gambar --}}
        <div class="pkg-modal__media">
          <img src="{{ $imgUrl }}" alt="{{ $pkg->nama_paket }}">
        </div>

        {{-- Kanan: Konten --}}
        <div class="pkg-modal__content">
          <h3 class="pkg-title" id="pkgModalTitle-{{ $pkg->id }}">{{ strtoupper($pkg->nama_paket) }}</h3>

          <div class="pkg-price">
            <span class="price-final">Rp {{ number_format($final, 0, ',', '.') }}</span>
            @if($hasDisc)
              <span class="price-original">Rp {{ number_format($pkg->harga, 0, ',', '.') }}</span>
            @endif
          </div>

          <div class="pkg-grid">
            <div class="pkg-item">
              <i class="fas fa-clock"></i>
              <div>
                <h5>Durasi</h5>
                <p>{{ (int)$pkg->durasi }} jam</p>
              </div>
            </div>
            <div class="pkg-item">
              <i class="fas fa-palette"></i>
              <div>
                <h5>Konsep</h5>
                <p>{{ $pkg->konsep ?: '-' }}</p>
              </div>
            </div>
            <div class="pkg-item">
              <i class="fas fa-sticky-note"></i>
              <div>
                <h5>Notes</h5>
                <p>{{ $pkg->notes ?: '-' }}</p>
              </div>
            </div>
            <div class="pkg-item">
              <i class="fas fa-list-alt"></i>
              <div>
                <h5>Rules</h5>
                <p>{{ $pkg->rules ?: '-' }}</p>
              </div>
            </div>
          </div>

          <div class="pkg-desc">
            <h5><i class="fas fa-align-left"></i> Deskripsi Paket</h5>
            <p>{{ $pkg->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach
