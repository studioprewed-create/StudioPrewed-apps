<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="gallery-container">
            <div class="gallery-track">
                @if($temas->isEmpty())
                    @for($i=1;$i<=7;$i++)
                        <div class="gallery-item" data-index="{{ $i-1 }}">
                            <img src="{{ asset('asset/IMGhome/bg'.$i.'.jpg') }}" alt="Tema default {{ $i }}">
                            <div class="gallery-overlay">
                                <h3>Potret</h3>
                                <p>Deskripsi</p>
                                <p><strong>Harga:</strong> Rp0</p>
                                <button type="button" class="btn-theme-detail" disabled>Detail</button>
                            </div>
                        </div>
                    @endfor
                @else
                    @foreach($temas as $t)
                        @php
                            $images = $t->images ? json_decode($t->images, true) : [];
                            $first  = count($images) ? asset('storage/'.$images[0]) : asset('asset/IMGhome/bg1.jpg');
                        @endphp
                        <div class="gallery-item" data-index="{{ $loop->index }}">
                            <img src="{{ $first }}" alt="{{ $t->nama }}">
                            <div class="gallery-overlay">
                                <h3>{{ $t->nama }}</h3>
                                <p>{{ $t->detail }}</p>
                                <p><strong>Harga:</strong> Rp{{ number_format($t->harga,0,',','.') }}</p>
                                <button type="button" class="btn-detail" data-open="#temaModal-{{ $t->id }}">
                                    Detail
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="carousel-nav">
                <div class="nav-btn prev"><i class="fas fa-chevron-left"></i></div>
                <div class="nav-btn next"><i class="fas fa-chevron-right"></i></div>
            </div>
        </div>
    </div>
</section>

{{-- MODAL TEMA/BAJU --}}
@include('HOMEPAGES.MODAL.TemaBajuModal', ['temas' => $temas])