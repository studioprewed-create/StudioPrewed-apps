<section class="services-section" id="about" aria-labelledby="portrait-title">
    <div class="container about-wrap">
        <header class="head" data-reveal>
            <span class="eyebrow">Portrait Specialist</span>
            <h2 id="portrait-title">WE ARE <span class="hl">PORTRAIT</span> ARTISTS</h2>
            <p>Kami menghadirkan keindahan setiap individu melalui pencahayaan dramatis, komposisi artistic, dan sentuhan editing yang timeless.</p>
        </header>

    @php
        $model1 = $aboutus->where('model_type','model1')->sortBy('order');
        $model2 = $aboutus->where('model_type','model2')->sortBy('order');
        $model3 = $aboutus->where('model_type','model3')->sortBy('order');
    @endphp

    <div class="about-mosaic">
        @if($model1->isNotEmpty())
            @foreach($model1 as $item)
                @if(!empty($item->all_image_urls))
                    <div class="mosaic-item mosaic-main">
                        <img src="{{ $item->all_image_urls[0] ?? asset('asset/IMGhome/default.jpg') }}" alt="Portrait">
                    </div>
                @endif

                <div class="mosaic-text mosaic-side-text">
                    <h3>{{ $item->title }}</h3>
                    <h4>{{ $item->subtitle }}</h4>
                    <p>{{ $item->description }}</p>
                </div>
            @endforeach
        @endif

        @if($model2->isNotEmpty())
            <div class="mosaic-text mosaic-text-1">
                <div class="mosaic-text-inner">
                    @foreach($model2 as $data)
                        <div class="mosaic-text-1a">
                            <h3>{{ $data->title }}</h3>
                            <h4>{{ $data->subtitle }}</h4>
                            <p>{{ $data->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            @foreach($model2 as $data)
                @foreach($data->all_image_urls as $key => $img)
                    @if($key < 2 && $img)
                        <div class="mosaic-item mosaic-item-{{ $key + 1 }}">
                            <img src="{{ $img }}" alt="Portrait">
                        </div>
                    @endif
                @endforeach
            @endforeach
        @endif

        @if($model3->isNotEmpty())
            @foreach($model3 as $data)
                @foreach($data->all_image_urls as $key => $img)
                    @if($key < 2 && $img)
                        <div class="mosaic-item mosaic-item-{{ $key + 3 }}">
                            <img src="{{ $img }}" alt="Portrait">
                        </div>
                    @endif
                @endforeach
            @endforeach

            <div class="mosaic-text mosaic-text-2">
                <div class="mosaic-text-inner">
                    @foreach($model3 as $data)
                        <div class="mosaic-text-2a">
                            <h3>{{ $data->title }}</h3>
                            <h4>{{ $data->subtitle }}</h4>
                            <p>{{ $data->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="about-stats-mosaic">
        <div class="stat-item-mosaic">
            <span class="stat-number-mosaic">3000+</span>
            <span class="stat-label-mosaic">Portrait Sessions</span>
        </div>
        <div class="stat-item-mosaic">
            <span class="stat-number-mosaic">8</span>
            <span class="stat-label-mosaic">Years Experience</span>
        </div>
        <div class="stat-item-mosaic">
            <span class="stat-number-mosaic">15+</span>
            <span class="stat-label-mosaic">Portrait Styles</span>
        </div>
        <div class="stat-item-mosaic">
            <span class="stat-number-mosaic">99%</span>
            <span class="stat-label-mosaic">Client Satisfaction</span>
        </div>
    </div>
</section>