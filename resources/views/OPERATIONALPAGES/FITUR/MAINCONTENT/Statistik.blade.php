<div class="has-sub-domain">

    <div class="sub-domain">

        @include('OPERATIONALPAGES.FITUR.MAPPING.SUBSIDEBAR.Statistik')

        <div class="sub-content-wrap">

            @if(isset($subpage))
                <div id="sub-content" class="sub-content" data-current-page="{{ $subpage }}">

                    @if(View::exists("OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage"))
                        @include("OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage")
                    @else
                        <div class="alert alert-warning">
                            Sub halaman "{{ $subpage }}" belum dibuat.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>