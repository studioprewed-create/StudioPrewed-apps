<div class="marquee" role="log" aria-live="polite">
        <div class="inner">
            @php
            $marqueeItems = (isset($marquees) && $marquees->isNotEmpty())
                ? $marquees->map(fn($m) => (object)['text'=>$m->text, 'icon_class'=>$m->icon_class ?? null])
                : collect([
                    (object)['text'=>'Playful photography','icon_class'=>null],
                    (object)['text'=>'Warm pastel tones','icon_class'=>null],
                    (object)['text'=>'Organic shapes','icon_class'=>null],
                    (object)['text'=>'Airy composition','icon_class'=>null],
                    (object)['text'=>'Fun posing','icon_class'=>null],
                    (object)['text'=>'Studio & Outdoor','icon_class'=>null],
                    (object)['text'=>'Family • Couple • Portrait','icon_class'=>null],
                    (object)['text'=>'Cinematic yet friendly','icon_class'=>null],
                ]);
            $looped = $marqueeItems->concat($marqueeItems);
            @endphp

            @foreach($looped as $item)
            <span class="pill">
                @if(!empty($item->icon_class))
                <i class="{{ $item->icon_class }}" aria-hidden="true"></i>
                @endif
                {{ $item->text }}
            </span>
            @endforeach
        </div>
    </div>