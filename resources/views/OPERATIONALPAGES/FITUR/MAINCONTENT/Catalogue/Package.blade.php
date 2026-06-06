<div class="page-header">
    <div>
        <h1>Catalogue</h1>
        <div class="subtitle">Kelola paket katalog beserta tema baju yang terkait</div>
            <i class="fa-solid fa-plus"></i> Tambah Package
        </button>
        <button type="button" class="btn btn-primary" id="btnOpenCreateTema">
            <i class="fa-solid fa-plus"></i> Tambah Tema Baju
        </button>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <strong>Terjadi kesalahan!</strong>
        <ul class="mt-8">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="tables">
    <div>
        <div class="h3">Packages</div>

        @if(($packages ?? collect())->isEmpty())
            <div class="alert alert-info">
                <i class="fa-solid fa-circle-info"></i>
                Belum ada package. Klik <b>Tambah Package</b> untuk menambahkan.
            </div>
        @else
            <div class="grid-cards">
                @foreach($packages as $p)
                    <div class="card-elev">
                        <div class="ratio-16x10">
                            <img src="{{ $p->image_url }}" alt="{{ $p->nama_paket }}">
                        </div>

                        <div class="card-body">
                            <div class="card-head package-head">
                                <div>
                                    <div class="title">{{ $p->nama_paket }}</div>
                                    @if($p->discount > 0)
                                        <div class="price-strike">
                                            Rp {{ number_format($p->harga,0,',','.') }}
                                        </div>
                                        <div class="price">
                                            Rp {{ number_format($p->final_price,0,',','.') }}
                                            <span class="disc-pill">-{{ rtrim(rtrim(number_format($p->discount,2), '0'),'.') }}%</span>
                                        </div>
                                    @else
                                        <div class="price">Rp {{ number_format($p->harga,0,',','.') }}</div>
                                    @endif
                                </div>

                                <span class="role-badge {{ $p->active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $p->active ? 'ACTIVE' : 'INACTIVE' }}
                                </span>
                            </div>

                            @if($p->durasi)
                                <div class="meta muted">
                                    <i class="fa-regular fa-clock"></i> Durasi: {{ $p->durasi }} menit
                                </div>
                            @endif

                            @if($p->deskripsi)
                                <p class="muted">
                                    {{ \Illuminate\Support\Str::limit($p->deskripsi, 120) }}
                                </p>
                            @endif

                            @if($p->attire_items->isNotEmpty())
                                <div class="chips package-themes">
                                    @foreach($p->attire_items as $theme)
                                        <span class="theme-chip">{{ $theme->nama }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="card-actions">
                                {{-- Edit Package --}}
                                <button type="button"
                                    class="btn btn-outline btn-edit-package"
                                    title="Edit package"
                                    data-id="{{ $p->id }}"
                                    data-nama="{{ $p->nama_paket }}"
                                    data-harga="{{ $p->harga }}"
                                    data-discount="{{ $p->discount }}"
                                    data-durasi="{{ $p->durasi }}"
                                    data-deskripsi='@json($p->deskripsi)'
                                    data-notes="{{ $p->notes }}"
                                    data-konsep='@json($p->konsep)'
                                    data-rules="{{ $p->rules }}"
                                    data-attire-ids='@json($p->attire_ids)'
                                    data-label-ids='@json($p->label_id)'
                                    data-tac-ids='@json($p->tac_ids)'>
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                {{-- Hapus Package --}}
                                <form action="{{ route('executive.packages.destroy', $p->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus package {{ $p->nama_paket }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@include('OPERATIONALPAGES.FITUR.MODAL.ModalCatalogue')