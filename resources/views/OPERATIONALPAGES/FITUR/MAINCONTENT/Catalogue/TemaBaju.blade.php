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
    <div>
        <div class="h3">Attire</div>

        @if(($temas ?? collect())->isEmpty())
            <div class="alert alert-info">
                <i class="fa-solid fa-circle-info"></i>
                Belum ada Attire.
            </div>
        @else
            <div class="grid-cards sm">
                @foreach($temas as $t)
                    <div class="card-elev">
                        <div class="ratio-3x4">
                            <img src="{{ $t->main_image }}"
                                alt="{{ $t->nama }}">
                        </div>

                        <div class="card-body">
                            <div class="card-head">
                                <div>
                                    <div class="title">
                                        {{ $t->nama }}
                                    </div>

                                    <div class="small muted">
                                        {{ $t->kode }}
                                    </div>
                                </div>

                                <span class="role-badge {{ $t->active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $t->active ? 'ACTIVE' : 'INACTIVE' }}
                                </span>
                            </div>

                            <div class="price">
                                Rp {{ number_format($t->harga, 0, ',', '.') }}
                            </div>

                            <div class="chips">
                                <span>
                                    <i class="fa-solid fa-user-tie"></i>

                                    {{ $t->designerBrand?->nama_brand
                                        ?? $t->designer
                                        ?? '-' }}
                                </span>

                                <span>
                                    <i class="fa-solid fa-layer-group"></i>

                                    {{ $t->tipeAttire?->content
                                        ?? $t->tipe
                                        ?? '-' }}
                                </span>

                                @if($t->warna)
                                    <span>
                                        <i class="fa-solid fa-palette"></i>
                                        {{ $t->warna }}
                                    </span>
                                @endif

                                <span>
                                    {{ strtoupper($t->status ?? 'ready') }}
                                </span>
                            </div>

                            @if($t->label_items->isNotEmpty())
                                <div class="chips">
                                    @foreach($t->label_items as $label)
                                        <span>
                                            {{ $label->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="card-actions">
                                <button type="button"
                                    class="btn btn-outline btn-edit-tema"

                                    data-id="{{ $t->id }}"
                                    data-nama="{{ $t->nama }}"
                                    data-kode="{{ $t->kode }}"
                                    data-harga="{{ $t->harga }}"

                                    data-brand-id="{{ $t->data_brand_id }}"
                                    data-konsep-attire-id="{{ $t->konsep_attire_id }}"

                                    data-warna="{{ $t->warna }}"
                                    data-ukuran-pria="{{ $t->ukuran_pria }}"
                                    data-ukuran-wanita="{{ $t->ukuran_wanita }}"

                                    data-status="{{ $t->status }}"
                                    data-order="{{ $t->order }}"
                                    data-active="{{ $t->active ? 1 : 0 }}"

                                    data-label-ids='{{ e(json_encode($t->label_ids ?? [])) }}'

                                    data-details='{{ e(
                                        $t->details
                                            ->map(fn($detail) => [
                                                "group" => $detail->group,
                                                "content" => $detail->content,
                                                "order" => $detail->order,
                                            ])
                                            ->values()
                                            ->toJson()
                                    ) }}'

                                    data-images='{{ e(json_encode($t->all_image_urls)) }}'>

                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <form action="{{ route('executive.tema_baju.destroy', $t->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin hapus Attire {{ $t->nama }}?')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger"
                                        type="submit">

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