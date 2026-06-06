<div class="stats-page library-master-page">
    <div class="stats-header">
        <div>
            <h1>Package Catalogue</h1>
            <p>Kelola master data package Studio Prewed</p>
        </div>

        <div class="stats-header-actions">
            <div class="stats-badge">
                <i class="fas fa-box-open"></i>
                Package Catalogue
            </div>
            <button type="button" class="btn btn-primary" id="btnOpenCreatePackage">
                <i class="fa-solid fa-plus"></i> Tambah Package
            </button>
        </div>
    </div>
</div>

@php
    $packageCollection = $packages ?? collect();
    $packageActive = $packageCollection->where('active', true)->count();
    $packageInactive = $packageCollection->where('active', false)->count();
@endphp

<div class="stats-row">
    <div class="stats-card">
        <div class="stats-value">{{ $packageCollection->count() }}</div>
        <div class="stats-label">Total Package</div>
    </div>
    <div class="stats-card">
        <div class="stats-value">{{ $packageActive }}</div>
        <div class="stats-label">Package Aktif</div>
    </div>
    <div class="stats-card">
        <div class="stats-value">{{ $packageInactive }}</div>
        <div class="stats-label">Package Nonaktif</div>
    </div>
</div>

@include('OPERATIONALPAGES.FITUR.Notifikasi.Alert')

<div class="tables">

    @if (($packages ?? collect())->isEmpty())
        <div class="alert alert-info">
            <i class="fa-solid fa-circle-info"></i>
            Belum ada package. Klik <b>Tambah Package</b> untuk menambahkan.
        </div>
    @else
        <div class="grid-cards">
            @foreach ($packages as $p)
                <article class="card-elev">
                    <div class="card-grid">
                        <div class="card-media">
                            <div class="ratio-3x4">
                                <img src="{{ $p->image_url }}" alt="{{ $p->nama_paket }}">
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="card-head package-head">
                                <div>
                                    <div class="title">{{ $p->nama_paket }}</div>
                                    @if ($p->discount > 0)
                                        <div class="price-strike">
                                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                                        </div>
                                        <div class="price">
                                            Rp {{ number_format($p->final_price, 0, ',', '.') }}
                                            <span
                                                class="disc-pill">-{{ rtrim(rtrim(number_format($p->discount, 2), '0'), '.') }}%</span>
                                        </div>
                                    @else
                                        <div class="price">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                                    @endif
                                </div>

                                <span class="role-badge {{ $p->active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $p->active ? 'ACTIVE' : 'INACTIVE' }}
                                </span>
                            </div>

                            <div class="meta muted card-meta-row">
                                @if ($p->durasi)
                                    <span><i class="fa-regular fa-clock"></i> {{ $p->durasi }} menit</span>
                                @endif
                                <span><i class="fa-solid fa-list-check"></i> {{ $p->attire_items->count() }}
                                    tema</span>
                            </div>

                            @if ($p->description_items->isNotEmpty())
                                <p class="muted package-description">
                                    {{ \Illuminate\Support\Str::limit($p->description_items->pluck('content')->implode(' • '), 160) }}
                                </p>
                            @endif

                            <div class="package-info-grid">
                                @if ($p->label_items->isNotEmpty())
                                    <div>
                                        <div class="info-label">Label</div>
                                        <div class="package-themes">
                                            @foreach ($p->label_items as $label)
                                                <span class="theme-chip">{{ $label->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($p->konsep_items->isNotEmpty())
                                    <div>
                                        <div class="info-label">Konsep</div>
                                        <div class="package-themes">
                                            @foreach ($p->konsep_items as $konsep)
                                                <span class="theme-chip">{{ $konsep->content }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($p->tac_items->isNotEmpty())
                                <div class="package-themes tac-tags">
                                    @foreach ($p->tac_items as $tac)
                                        <span
                                            class="theme-chip">{{ $tac->content ?? ($tac->name ?? 'TAC #' . $tac->id) }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="card-actions">
                                {{-- Edit Package --}}
                                <button type="button" class="btn btn-outline btn-edit-package" title="Edit package"
                                    data-id="{{ $p->id }}" data-nama="{{ $p->nama_paket }}"
                                    data-harga="{{ $p->harga }}" data-discount="{{ $p->discount }}"
                                    data-durasi="{{ $p->durasi }}" data-deskripsi='@json($p->deskripsi)'
                                    data-notes="{{ $p->notes }}" data-konsep='@json($p->konsep)'
                                    data-rules="{{ $p->rules }}" data-attire-ids='@json($p->attire_ids)'
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
                </article>
            @endforeach
        </div>
    @endif

</div>

@include('OPERATIONALPAGES.FITUR.MODAL.ModalCatalogue')
