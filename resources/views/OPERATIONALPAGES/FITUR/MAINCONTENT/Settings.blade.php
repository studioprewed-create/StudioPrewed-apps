<div class="settings-layout">
    @include('OPERATIONALPAGES.FITUR.MAPPING.SUBSIDEBAR.Settings')

    <div class="settings-content">

        @if(isset($subpage))
            <div id="sub-content" class="sub-content" data-current-page="{{ $subpage }}">
                @if(View::exists("OPERATIONALPAGES.FITUR.SUBCONTENT.$subpage"))
                    @include("OPERATIONALPAGES.FITUR.SUBCONTENT.$subpage")
                @else
                    <div class="alert alert-warning">
                        Sub halaman "{{ $subpage }}" belum dibuat.
                    </div>
                @endif

            </div>
        @endif

    </div>

</div>
