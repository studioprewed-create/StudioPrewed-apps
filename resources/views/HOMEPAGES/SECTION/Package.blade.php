<section class="packages-section" id="Booking">
    <div class="packages-header">
        <h2 class="packages-title">Paket Prewedding Kami</h2>
        <p class="packages-subtitle">Pilih paket terbaik untuk momen spesial Anda</p>
    </div>

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
                </div>

                {{-- Content Section --}}
                <div class="package-body">
                    {{-- Title --}}
                    <div class="package-header">
                        <h3 class="package-title">{{ ucfirst($pkg->nama_paket) }}</h3>
                    </div>

                    {{-- Features Grid --}}
                    <div class="package-features">
                        @if($pkg->durasi)
                            <div class="feature-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ (int) $pkg->durasi }} menit</span>
                            </div>
                        @endif
                        @if($pkg->attire_items && $pkg->attire_items->count())
                            <div class="feature-item">
                                <i class="fas fa-palette"></i>
                                <span>{{ $pkg->attire_items->count() }} tema</span>
                            </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($pkg->description_items && $pkg->description_items->count())
                        <p class="package-description">
                            {{ \Illuminate\Support\Str::limit($pkg->description_items->pluck('content')->implode(' • '), 100) }}
                        </p>
                    @endif

                    {{-- Labels & Konsep --}}
                    @if($pkg->label_items && $pkg->label_items->count())
                        <div class="package-tags">
                            @foreach($pkg->label_items->take(3) as $label)
                                <span class="tag">{{ $label->name }}</span>
                            @endforeach
                        </div>
                    @endif

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
