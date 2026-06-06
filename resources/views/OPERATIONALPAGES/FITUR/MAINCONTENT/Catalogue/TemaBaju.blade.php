<div class="page-header">
    <div>
        <h1>Catalogue</h1>
        <div class="subtitle">Kelola paket dan tema baju yang tampil di katalog</div>
    </div>
    <div class="header-actions">
        <button type="button" class="btn btn-secondary" id="btnOpenCreatePackage">
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
        {{-- =====================  TEMA BAJU  ===================== --}}
    <div>
        <div class="h3">Tema Baju</div>

        @if(($temas ?? collect())->isEmpty())
            <div class="alert alert-info">
                <i class="fa-solid fa-circle-info"></i>
                Belum ada tema baju. Klik <b>Tambah Tema Baju</b> untuk menambahkan.
            </div>
        @else
            <div class="grid-cards sm">
                @foreach($temas as $t)
                    <div class="card-elev">
                        <div class="ratio-3x4">
                            <img src="{{ $t->main_image }}" alt="{{ $t->nama }}">
                        </div>

                        <div class="card-body">
                            <div class="card-head">
                                <div>
                                    <div class="title">{{ $t->nama }}</div>
                                    <div class="small muted">{{ $t->kode }}</div>
                                </div>
                                <span class="role-badge {{ $t->active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $t->active ? 'ACTIVE' : 'INACTIVE' }}
                                </span>
                            </div>

                            <div class="price">Rp {{ number_format($t->harga,0,',','.') }}</div>

                            <div class="chips">
                                <span><i class="fa-solid fa-ruler"></i> {{ $t->ukuran }}</span>
                                <span><i class="fa-solid fa-layer-group"></i> {{ $t->tipe }}</span>
                                <span><i class="fa-solid fa-user-tie"></i> {{ $t->designer }}</span>
                            </div>

                            @if($t->detail)
                                <p class="muted">{{ \Illuminate\Support\Str::limit($t->detail, 120) }}</p>
                            @endif

                            <div class="card-actions">
                                {{-- Edit Tema --}}
                                <button type="button"
                                    class="btn btn-outline btn-edit-tema"
                                    title="Edit tema baju"
                                    data-id="{{ $t->id }}"
                                    data-nama="{{ $t->nama }}"
                                    data-kode="{{ $t->kode }}"
                                    data-harga="{{ $t->harga }}"
                                    data-ukuran="{{ $t->ukuran }}"
                                    data-tipe="{{ $t->tipe }}"
                                    data-designer="{{ $t->designer }}"
                                    data-detail="{{ $t->detail }}"
                                    data-images='@json($t->all_image_urls)'>
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                {{-- Hapus --}}
                                <form action="{{ route('executive.tema_baju.destroy', $t->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus tema {{ $t->nama }}?')">
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