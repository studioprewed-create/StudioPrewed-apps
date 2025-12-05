<section class="packages-section" id="Booking">
    <div class="packages-grid" id="packages-container">
        @forelse($packages as $pkg)
            @php
                $hasDisc = ($pkg->discount ?? 0) > 0;
                $final   = $pkg->final_price ?? $pkg->harga;
                $imgUrl  = $pkg->image_url ?? 'https://via.placeholder.com/600x400?text=No+Image';
            @endphp

            <div class="package-card">
                <div class="package-content">
                    {{-- Gambar --}}
                    <div class="package-image" onclick="expandImage('{{ $imgUrl }}')">
                        <img src="{{ $imgUrl }}" alt="{{ $pkg->nama_paket }}">
                    </div>

                    {{-- Detail --}}
                    <div class="package-details">
                        <h3 class="package-title">{{ strtoupper($pkg->nama_paket) }}</h3>

                        <div class="package-features">
                            <div class="feature">
                                <i class="fas fa-clock"></i>
                                Durasi: {{ (int)$pkg->durasi }} menit
                            </div>
                        </div>

                        <div class="package-price">
                            <div class="price-box">
                                <span class="price-final">Rp {{ number_format($final, 0, ',', '.') }}</span>
                                @if($hasDisc)
                                    <span class="price-original">Rp {{ number_format($pkg->harga, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="package-actions">
                            <button type="button" class="btn-detail" data-open="#pkgModal-{{ $pkg->id }}">
                                Detail
                            </button>
                            <a href="#bookingWizard"
                                class="btn-primary btn-choose-package"
                                data-package-id="{{ $pkg->id }}">
                                Pilih Paket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p style="opacity:.8">Belum ada paket.</p>
        @endforelse
    </div>
</section>

<div id="imageModal" class="modal" style="display:none;">
    <span class="close" data-close-img>&times;</span>
    <img class="modal-content" id="expandedImage" alt="Preview Gambar Paket">
</div>
@include('HOMEPAGES.MODAL.PackageModal')