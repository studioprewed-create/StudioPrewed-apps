<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="gallery-grid">

            @if($temas->isEmpty())
                @for($i=1;$i<=6;$i++)
                    <div class="gallery-card">
                        <img src="{{ asset('asset/IMGhome/bg'.$i.'.jpg') }}" alt="Tema default {{ $i }}">

                        <div class="gallery-overlay">
                            <h3>Potret</h3>
                            <p>Deskripsi</p>
                            <p></p>
                            <button class="btn-detail" disabled>Detail</button>
                        </div>
                    </div>
                @endfor
            @else
                @foreach($temas as $t)
                    @php
                        $images = $t->images ? json_decode($t->images, true) : [];
                        $first  = count($images) ? asset('public/storage/'.$images[0]) : asset('asset/IMGhome/bg1.jpg');
                    @endphp

                    <div class="gallery-card">
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
    </div>
</section>

{{-- MODAL --}}
@include('HOMEPAGES.MODAL.TemaBajuModal', ['temas' => $temas])