<div class="floating-middlebar">
    <div class="middlebar-container">
        <div class="gallery-filter">

            <button class="filter-btn active" data-filter="all">
                <i class="fas fa-globe"></i>
                <span class="label">All</span>
            </button>

            @foreach($filters as $nama => $slug)
                <button class="filter-btn" data-filter="{{ $slug }}">
                    <span class="label">{{ $nama }}</span>
                </button>
            @endforeach

        </div>
    </div>
</div>

<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="gallery-grid">

            @if($temas->isEmpty())
                @for($i=1;$i<=6;$i++)
                    <div class="gallery-card" data-category="default">
                        <img src="{{ asset('asset/IMGhome/bg'.$i.'.jpg') }}">

                        <div class="gallery-overlay">
                            <h3>Potret</h3>
                            <p>Deskripsi</p>
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

                    <div class="gallery-card" data-category="{{ $t->slug }}">
                        <img src="{{ $first }}" alt="{{ $t->nama }}">

                        <div class="gallery-overlay">
                            <h3>{{ $t->nama }}</h3>
                            <p>{{ $t->detail }}</p>

                            <button type="button"
                                class="btn-detail"
                                data-open="#temaModal-{{ $t->id }}">
                                Detail
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
</section>

@include('HOMEPAGES.MODAL.TemaBajuModal', ['temas' => $temas])