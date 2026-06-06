<section class="packages-section" id="Booking">

    <div class="packages-grid" id="packages-container">
        @forelse($packages as $pkg)
            @php
                $hasDisc = ($pkg->discount ?? 0) > 0;
                $final = $pkg->final_price ?? $pkg->harga;
                $imgUrl = $pkg->image_url ?? 'https://via.placeholder.com/600x400?text=No+Image';
            @endphp

            <article class="package-card" role="article">
                {{-- Image Section --}}
                <div class="package-image-wrapper" data-open="#pkgModal-{{ $pkg->id }}">
                    <div class="package-image">
                        <img src="{{ $imgUrl }}" alt="{{ $pkg->nama_paket }}" loading="lazy">
                        <div class="image-overlay"></div>
                    </div>
                    @if($hasDisc)
                        <div class="discount-badge">-{{ rtrim(rtrim(number_format($pkg->discount,2), '0'),'.') }}%</div>
                    @endif
                    @if($pkg->label_items && $pkg->label_items->isNotEmpty())
                        <div class="Label-badge" style="background-color: {{ $pkg->label_items->first()->color ?? '#888' }};">
                            {{ $pkg->label_items->first()->name ?? 'No Label' }}
                        </div>
                    @endif
                </div>

                {{-- Content Section --}}
                <div class="package-body">
                    {{-- Title --}}
                    <h3 class="package-title">{{ ucfirst($pkg->nama_paket) }}</h3>

                    {{-- Price Section --}}
                    <div class="package-price-section">
                        <div class="price-block">
                            @if($hasDisc)
                                <span class="price-original">Rp {{ number_format($pkg->harga, 0, ',', '.') }}</span>
                            @endif
                            <span class="price-final">Rp {{ number_format(round($final, -4), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- CTA Button --}}
                    <button type="button" class="btn-view-detail" data-open="#pkgModal-{{ $pkg->id }}" aria-label="Lihat detail paket {{ $pkg->nama_paket }}">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <i class="fas fa-box"></i>
                <p>Belum ada paket tersedia.</p>
            </div>
        @endforelse
    </div>
</section>

<div id="imageModal" class="modal" style="display:none;">
    <span class="close" data-close-img>&times;</span>
    <img class="modal-content" id="expandedImage" alt="Preview Gambar Paket">
</div>
@include('HOMEPAGES.MODAL.PackageModal')
