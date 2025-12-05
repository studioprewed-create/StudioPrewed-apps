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
                            <div class="card-head">
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
                                    data-deskripsi="{{ $p->deskripsi }}"
                                    data-notes="{{ $p->notes }}"
                                    data-konsep="{{ $p->konsep }}"
                                    data-rules="{{ $p->rules }}">
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
                                    data-images='@json($t->images_array)'>
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

{{-- ===================  MODAL: CREATE PACKAGE  =================== --}}
<div class="custom-modal-backdrop" id="backdropCreatePackage"></div>
<div class="custom-modal" id="modalCreatePackage" aria-hidden="true">
    <div class="modal-content modal-xl">
        <div class="modal-header">
            <h5>Tambah Package</h5>
            <button class="btn btn-secondary" type="button" id="btnCloseCreatePackage">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('executive.packages.store') }}" method="POST" enctype="multipart/form-data" id="formCreatePackage">
                @csrf

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-heading"></i> Nama Paket</label>
                        <input type="text" class="form-control" name="nama_paket" required placeholder="Paket Prewedding Premium" value="{{ old('nama_paket') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-clock"></i> Durasi (menit)</label>
                        <input type="number" class="form-control" name="durasi" placeholder="120" value="{{ old('durasi') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tag"></i> Harga (Rp)</label>
                        <input type="number" step="0.01" class="form-control" name="harga" required placeholder="0" value="{{ old('harga') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-percent"></i> Diskon (%)</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control" name="discount" placeholder="0" value="{{ old('discount') }}">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-sticky-note"></i> Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan">{{ old('notes') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-palette"></i> Konsep</label>
                        <textarea name="konsep" class="form-control" rows="2" placeholder="Konsep foto yang ditawarkan">{{ old('konsep') }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-align-left"></i> Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan detail paket">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-list-alt"></i> Rules</label>
                    <textarea name="rules" class="form-control" rows="2" placeholder="Syarat dan ketentuan paket">{{ old('rules') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-image"></i> Gambar Package</label>
                    <div class="image-upload-container" id="uploadDropPackage">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Klik atau seret gambar ke sini</p>
                        <small>Format: JPG, PNG, WEBP | Maks: 2MB</small>
                        <input type="file" name="images" id="inputPackageImage" accept="image/*" class="file-overlay">
                    </div>
                    <div id="previewPackageImage" class="img-preview-box">
                        <img src="" alt="Preview">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" id="btnCloseCreatePackage2">Batal</button>
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================  MODAL: CREATE TEMA BAJU  =================== --}}
<div class="custom-modal-backdrop" id="backdropCreateTema"></div>
<div class="custom-modal" id="modalCreateTema" aria-hidden="true">
    <div class="modal-content modal-xl">
        <div class="modal-header">
            <h5>Tambah Tema Baju</h5>
            <button class="btn btn-secondary" type="button" id="btnCloseCreateTema">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('executive.tema_baju.store') }}" method="POST" enctype="multipart/form-data" id="formCreateTema">
                @csrf

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-heading"></i> Nama</label>
                        <input type="text" class="form-control" name="nama" required placeholder="Classic Wedding Gold" value="{{ old('nama') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-barcode"></i> Kode</label>
                        <input type="text" class="form-control" name="kode" required placeholder="CWG-01" value="{{ old('kode') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tag"></i> Harga (Rp)</label>
                        <input type="number" step="0.01" class="form-control" name="harga" required placeholder="0" value="{{ old('harga') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-ruler"></i> Ukuran</label>
                        <input type="text" class="form-control" name="ukuran" required placeholder="S, M, L, All Size" value="{{ old('ukuran') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-layer-group"></i> Tipe</label>
                        <input type="text" class="form-control" name="tipe" required placeholder="Formal, Casual, Traditional" value="{{ old('tipe') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-user-tie"></i> Designer</label>
                        <input type="text" class="form-control" name="designer" required placeholder="Nama desainer / vendor" value="{{ old('designer') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-sticky-note"></i> Detail</label>
                    <textarea name="detail" class="form-control" rows="3" required placeholder="Detail bahan, warna, model, dan nuansa tema">{{ old('detail') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-image"></i> Gambar (bisa pilih banyak)</label>
                    <div class="image-upload-container" id="uploadDrop">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Klik atau seret gambar ke sini</p>
                        <small>Format: JPG, PNG, WEBP | Maks: 2MB per gambar</small>
                        <input type="file" name="images[]" id="inputImages" accept="image/*" multiple class="file-overlay">
                    </div>
                    <div id="previewImages" class="thumbs-wrap"></div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" id="btnCloseCreateTema2">Batal</button>
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================  MODAL: EDIT PACKAGE  =================== --}}
<div class="custom-modal-backdrop" id="backdropEditPackage"></div>
<div class="custom-modal" id="modalEditPackage" aria-hidden="true">
    <div class="modal-content modal-xl">
        <div class="modal-header">
            <h5>Edit Package</h5>
            <button class="btn btn-secondary" type="button" id="btnCloseEditPackage">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" id="editPackageForm" data-base-url="{{ url('/executive/packages') }}">
                @csrf
                @method('PUT')

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-heading"></i> Nama Paket</label>
                        <input type="text" class="form-control" name="nama_paket" id="ep-nama" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-clock"></i> Durasi (menit)</label>
                        <input type="number" class="form-control" name="durasi" id="ep-durasi">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tag"></i> Harga (Rp)</label>
                        <input type="number" step="0.01" class="form-control" name="harga" id="ep-harga" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-percent"></i> Diskon (%)</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control" name="discount" id="ep-discount">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-sticky-note"></i> Notes</label>
                        <textarea name="notes" class="form-control" rows="2" id="ep-notes"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-palette"></i> Konsep</label>
                        <textarea name="konsep" class="form-control" rows="2" id="ep-konsep"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-align-left"></i> Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" id="ep-deskripsi"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-list-alt"></i> Rules</label>
                    <textarea name="rules" class="form-control" rows="2" id="ep-rules"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-image"></i> Ganti Gambar (opsional)</label>
                    <div class="image-upload-container" id="uploadDropPackageEdit">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Klik atau seret gambar ke sini</p>
                        <small>Format: JPG, PNG, WEBP | Maks: 2MB</small>
                        <input type="file" name="images" id="ep-image" accept="image/*" class="file-overlay">
                    </div>
                    <div id="previewPackageImageEdit" class="img-preview-box">
                        <img src="" alt="Preview">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" id="btnCloseEditPackage2">Batal</button>
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="custom-modal-backdrop" id="backdropEditTema"></div>
<div class="custom-modal" id="modalEditTema" aria-hidden="true">
    <div class="modal-content modal-xl">
        <div class="modal-header">
            <h5>Edit Tema Baju</h5>
            <button class="btn btn-secondary" type="button" id="btnCloseEditTema">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" id="editTemaForm" data-base-url="{{ url('/executive/tema-baju') }}">
                @csrf
                @method('PUT')

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-heading"></i> Nama</label>
                        <input type="text" class="form-control" name="nama" id="et-nama" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-barcode"></i> Kode</label>
                        <input type="text" class="form-control" name="kode" id="et-kode" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tag"></i> Harga (Rp)</label>
                        <input type="number" step="0.01" class="form-control" name="harga" id="et-harga" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-ruler"></i> Ukuran</label>
                        <input type="text" class="form-control" name="ukuran" id="et-ukuran" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-layer-group"></i> Tipe</label>
                        <input type="text" class="form-control" name="tipe" id="et-tipe" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-user-tie"></i> Designer</label>
                        <input type="text" class="form-control" name="designer" id="et-designer" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-sticky-note"></i> Detail</label>
                    <textarea name="detail" class="form-control" rows="3" id="et-detail" required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-image"></i> Ganti Gambar (opsional)</label>
                    <div class="image-upload-container" id="uploadDropTemaEdit">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Pilih ulang gambar (opsional)</p>
                        <small>Format: JPG, PNG, WEBP | Maks: 2MB per gambar</small>
                        <input type="file" name="images[]" id="et-images" accept="image/*" multiple class="file-overlay">
                    </div>
                    <div id="previewImagesEdit" class="thumbs-wrap"></div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" id="btnCloseEditTema2">Batal</button>
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
