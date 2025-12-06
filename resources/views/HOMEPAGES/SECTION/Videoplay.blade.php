 @php
    use Illuminate\Support\Str;
@endphp

 <section class="hero" id="home">
        <div class="hero-carousel">
            <div class="carousel-inner">
                @if($slides->isEmpty())
                    {{-- fallback ke default video --}}
                    @for($i=1;$i<=3;$i++)
                        <div class="carousel-item {{ $i===1 ? 'active' : '' }}">
                            <video autoplay muted loop playsinline>
                                <source src="{{ asset('asset/VIDhome/video'.$i.'.mp4') }}" type="video/mp4">
                                Browser tidak mendukung video.
                            </video>
                        </div>
                    @endfor
                @else
                    @foreach($slides as $slide)
                        @php
                            $path = $slide->image ? asset('public/storage/'.$slide->image) : null;
                        @endphp

                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            @if($path)
                                @if(Str::endsWith($slide->image, ['.mp4','.mov','.avi','.webm']))
                                    <div class="video-wrapper">
                                        <video autoplay muted loop playsinline>
                                            <source src="{{ $path }}" type="video/mp4">
                                            Browser tidak mendukung video.
                                        </video>
                                    </div>
                                @else
                                    <div class="ci" style="background-image:url('{{ $path }}');"></div>
                                @endif
                            @else
                                <div class="ci" style="background-image:url('{{ asset('asset/IMGhome/bg1.jpg') }}');"></div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    

    @php
    // fallback images kalau service belum punya image
    $fallbacks = [
    'asset/IMGhome/bg1.jpg',
    'asset/IMGhome/bg2.jpg',
    'asset/IMGhome/bg3.jpg',
    'asset/IMGhome/bg4.jpg',
    'asset/IMGhome/bg5.jpg',
    'asset/IMGhome/bg6.jpg',
    ];
    @endphp