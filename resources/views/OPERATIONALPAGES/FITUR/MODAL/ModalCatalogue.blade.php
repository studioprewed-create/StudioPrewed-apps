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
            <form action="{{ route('executive.homepages.store', 'package') }}" method="POST"
                enctype="multipart/form-data" id="formCreatePackage">
                @csrf
                @php
                    $temas = $temas ?? collect();
                    $packageLabels = $packageLabels ?? collect();
                    $konsepAttires = $konsepAttires ?? collect();
                    $descPackages = $descPackages ?? collect();
                    $tacPackages = $tacPackages ?? collect();
                @endphp

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-heading"></i> Nama Paket</label>
                        <input type="text" class="form-control" name="nama_paket" required
                            placeholder="Paket Prewedding Premium" value="{{ old('nama_paket') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-clock"></i> Durasi (menit)</label>
                        <input type="number" class="form-control" name="durasi" placeholder="120"
                            value="{{ old('durasi') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tag"></i> Harga (Rp)</label>
                        <input type="number" step="0.01" class="form-control" name="harga" required
                            placeholder="0" value="{{ old('harga') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-percent"></i> Diskon (%)</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control"
                            name="discount" placeholder="0" value="{{ old('discount') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-sticky-note"></i> Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan">{{ old('notes') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-list-alt"></i> Rules</label>
                        <textarea name="rules" class="form-control" rows="2" placeholder="Aturan atau ketentuan khusus">{{ old('rules') }}</textarea>
                    </div>
                </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-list-alt"></i> Label Paket</label>
                        <div class="option-grid">
                            @foreach ($packageLabels as $label)
                                <label class="option-card">
                                    <input type="checkbox" name="label_id[]" value="{{ $label->id }}"
                                        @if (in_array($label->id, old('label_id', []))) checked @endif>
                                    <span class="option-text">{{ $label->name ?? $label->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="muted">Pilih label paket yang berlaku.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-palette"></i> Konsep</label>
                        <div class="option-grid">
                            @foreach ($konsepAttires as $konsep)
                                <label class="option-card">
                                    <input type="checkbox" name="konsep[]" value="{{ $konsep->id }}"
                                        @if (in_array($konsep->id, old('konsep', []))) checked @endif>
                                    <span class="option-text">{{ $konsep->content ?? $konsep->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="muted">Pilih satu atau beberapa konsep untuk paket ini.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Deskripsi</label>
                        <div class="option-grid">
                            @foreach ($descPackages as $desc)
                                <label class="option-card">
                                    <input type="checkbox" name="deskripsi[]" value="{{ $desc->id }}"
                                        @if (in_array($desc->id, old('deskripsi', []))) checked @endif>
                                    <span
                                        class="option-text">{{ $desc->content ?? ($desc->title ?? ($desc->nama ?? 'Deskripsi #' . $desc->id)) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="muted">Pilih deskripsi paket yang sesuai.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-list-alt"></i> TAC Package</label>
                        <div class="option-grid">
                            @foreach ($tacPackages as $tac)
                                <label class="option-card">
                                    <input type="checkbox" name="tac_ids[]" value="{{ $tac->id }}"
                                        @if (in_array($tac->id, old('tac_ids', []))) checked @endif>
                                    <span
                                        class="option-text">{{ $tac->content ?? ($tac->title ?? ($tac->name ?? ($tac->nama ?? 'TAC #' . $tac->id))) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="muted">Pilih satu atau beberapa TAC package.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tshirt"></i> Tema Baju</label>
                        <div class="option-grid">
                            @foreach ($temas as $tema)
                                <label class="option-card">
                                    <input type="checkbox" name="attire_ids[]" value="{{ $tema->id }}"
                                        @if (in_array($tema->id, old('attire_ids', []))) checked @endif>
                                    <span
                                        class="option-text">{{ $tema->nama }}{{ $tema->kode ? ' (' . $tema->kode . ')' : '' }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="muted">Pilih satu atau lebih tema baju yang tersedia untuk paket ini.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-image"></i> Gambar Package</label>
                        <div class="image-upload-container" id="uploadDropPackage">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Klik atau seret gambar ke sini</p>
                            <small>Format: JPG, PNG, WEBP | Maks: 2MB</small>
                            <input type="file" name="images" id="inputPackageImage" accept="image/*"
                                class="file-overlay">
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
            <form method="POST" enctype="multipart/form-data" id="editPackageForm"
                data-base-url="{{ url('/executive/homepages/update/package') }}">
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
                        <input type="number" step="0.01" class="form-control" name="harga" id="ep-harga"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-percent"></i> Diskon (%)</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control"
                            name="discount" id="ep-discount">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-sticky-note"></i> Notes</label>
                        <textarea name="notes" class="form-control" rows="2" id="ep-notes"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-list-alt"></i> Label Paket</label>
                        <div class="option-grid" id="ep-label_id">
                            @foreach ($packageLabels as $label)
                                <label class="option-card" for="ep-label-{{ $label->id }}">
                                    <input id="ep-label-{{ $label->id }}" type="checkbox" name="label_id[]"
                                        value="{{ $label->id }}">
                                    <span class="option-text">{{ $label->name ?? $label->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="muted">Pilih label paket yang berlaku.</small>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-palette"></i> Konsep</label>
                        <div class="option-grid" id="ep-konsep">
                            @foreach ($konsepAttires as $konsep)
                                <label class="option-card" for="ep-konsep-{{ $konsep->id }}">
                                    <input id="ep-konsep-{{ $konsep->id }}" type="checkbox" name="konsep[]"
                                        value="{{ $konsep->id }}">
                                    <span class="option-text">{{ $konsep->content ?? $konsep->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="muted">Pilih satu atau beberapa konsep untuk paket ini.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Deskripsi</label>
                        <div class="option-grid" id="ep-deskripsi">
                            @foreach ($descPackages as $desc)
                                <label class="option-card" for="ep-deskripsi-{{ $desc->id }}">
                                    <input id="ep-deskripsi-{{ $desc->id }}" type="checkbox" name="deskripsi[]"
                                        value="{{ $desc->id }}">
                                    <span
                                        class="option-text">{{ $desc->content ?? ($desc->title ?? ($desc->nama ?? 'Deskripsi #' . $desc->id)) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="muted">Pilih deskripsi paket yang sesuai.</small>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-list-alt"></i> TAC Package</label>
                        <div class="option-grid" id="ep-tac_ids">
                            @foreach ($tacPackages as $tac)
                                <label class="option-card" for="ep-tac-{{ $tac->id }}">
                                    <input id="ep-tac-{{ $tac->id }}" type="checkbox" name="tac_ids[]"
                                        value="{{ $tac->id }}">
                                    <span
                                        class="option-text">{{ $tac->content ?? ($tac->title ?? ($tac->name ?? ($tac->nama ?? 'TAC #' . $tac->id))) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="muted">Pilih satu atau beberapa TAC package.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tshirt"></i> Tema Baju</label>
                        <div class="option-grid" id="ep-attire_ids">
                            @foreach ($temas as $tema)
                                <label class="option-card" for="ep-attire-{{ $tema->id }}">
                                    <input id="ep-attire-{{ $tema->id }}" type="checkbox" name="attire_ids[]"
                                        value="{{ $tema->id }}">
                                    <span
                                        class="option-text">{{ $tema->nama }}{{ $tema->kode ? ' (' . $tema->kode . ')' : '' }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="muted">Pilih satu atau lebih tema baju yang tersedia untuk paket ini.</small>
                    </div>
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
            <form action="{{ route('executive.tema_baju.store') }}" method="POST" enctype="multipart/form-data"
                id="formCreateTema">
                @csrf

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-heading"></i> Nama</label>
                        <input type="text" class="form-control" name="nama" required
                            placeholder="Classic Wedding Gold" value="{{ old('nama') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-barcode"></i> Kode</label>
                        <input type="text" class="form-control" name="kode" required placeholder="CWG-01"
                            value="{{ old('kode') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tag"></i> Harga (Rp)</label>
                        <input type="number" step="0.01" class="form-control" name="harga" required
                            placeholder="0" value="{{ old('harga') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-ruler"></i> Ukuran</label>
                        <input type="text" class="form-control" name="ukuran" required
                            placeholder="S, M, L, All Size" value="{{ old('ukuran') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-layer-group"></i> Tipe</label>
                        <input type="text" class="form-control" name="tipe" required
                            placeholder="Formal, Casual, Traditional" value="{{ old('tipe') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-user-tie"></i> Designer</label>
                        <input type="text" class="form-control" name="designer" required
                            placeholder="Nama desainer / vendor" value="{{ old('designer') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-sticky-note"></i> Detail</label>
                    <textarea name="detail" class="form-control" rows="3" required
                        placeholder="Detail bahan, warna, model, dan nuansa tema">{{ old('detail') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-image"></i> Gambar (bisa pilih banyak)</label>
                    <div class="image-upload-container" id="uploadDrop">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Klik atau seret gambar ke sini</p>
                        <small>Format: JPG, PNG, WEBP | Maks: 2MB per gambar</small>
                        <input type="file" name="images[]" id="inputImages" accept="image/*" multiple
                            class="file-overlay">
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
            <form method="POST" enctype="multipart/form-data" id="editTemaForm"
                data-base-url="{{ url('/executive/tema-baju') }}">
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
                        <input type="number" step="0.01" class="form-control" name="harga" id="et-harga"
                            required>
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
                        <input type="file" name="images[]" id="et-images" accept="image/*" multiple
                            class="file-overlay">
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

<div class="custom-modal-backdrop" id="backdropCreateTacPackage"></div>
<div class="custom-modal" id="modalCreateTacPackage">

    <div class="modal-content">

        <div class="modal-header">

            <h5>Tambah TAC Package</h5>

            <button type="button" class="btn btn-secondary" id="btnCloseCreateTacPackage">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>

        <div class="modal-body">

            <form method="POST" action="{{ route('executive.homepages.store', 'tacpackage') }}">

                @csrf

                <div class="tac-form-card">

                    <div class="form-group">

                        <label class="form-label">
                            TAC Content
                        </label>

                        <textarea name="content" rows="4" class="form-control" required></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" id="btnCloseCreateTacPackage2">

                        Batal

                    </button>

                    <button class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="custom-modal-backdrop" id="backdropEditTacPackage"></div>
<div class="custom-modal" id="modalEditTacPackage">

    <div class="modal-content">

        <div class="modal-header">

            <h5>Edit TAC Package</h5>

            <button type="button" class="btn btn-secondary" id="btnCloseEditTacPackage">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>

        <div class="modal-body">

            <form id="editTacPackageForm" method="POST"
                data-base-url="{{ url('executive/homepages/update/tacpackage') }}">

                @csrf
                @method('PUT')

                <div class="tac-form-card">

                    <div class="form-group">

                        <label class="form-label">
                            TAC Content
                        </label>

                        <textarea id="et-content" name="content" rows="4" class="form-control" required></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" id="btnCloseEditTacPackage2">

                        Batal

                    </button>

                    <button class="btn btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="custom-modal-backdrop" id="backdropCreateKonsepAttire"></div>
<div class="custom-modal" id="modalCreateKonsepAttire">

    <div class="modal-content">

        <div class="modal-header">

            <h5>Tambah Konsep Attire</h5>

            <button type="button" class="btn btn-secondary" id="btnCloseCreateKonsepAttire">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>

        <div class="modal-body">

            <form method="POST" action="{{ route('executive.homepages.store', 'konsepattire') }}">

                @csrf

                <div class="tac-form-card">

                    <div class="form-group">

                        <label class="form-label">
                            Konsep Attire
                        </label>

                        <textarea name="content" rows="4" class="form-control" required></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" id="btnCloseCreateKonsepAttire2">

                        Batal

                    </button>

                    <button class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="custom-modal-backdrop" id="backdropEditKonsepAttire"></div>
<div class="custom-modal" id="modalEditKonsepAttire">

    <div class="modal-content">

        <div class="modal-header">

            <h5>Edit Konsep Attire</h5>

            <button type="button" class="btn btn-secondary" id="btnCloseEditKonsepAttire">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>

        <div class="modal-body">

            <form id="editKonsepAttireForm" method="POST"
                data-base-url="{{ url('executive/homepages/update/konsepattire') }}">

                @csrf
                @method('PUT')

                <div class="tac-form-card">

                    <div class="form-group">

                        <label class="form-label">
                            Konsep Attire
                        </label>

                        <textarea id="eka-content" name="content" rows="4" class="form-control" required></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" id="btnCloseEditKonsepAttire2">

                        Batal

                    </button>

                    <button class="btn btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="custom-modal-backdrop" id="backdropCreateDescPackage"></div>
<div class="custom-modal" id="modalCreateDescPackage">

    <div class="modal-content">

        <div class="modal-header">

            <h5>Tambah Deskripsi Package</h5>

            <button type="button" class="btn btn-secondary" id="btnCloseCreateDescPackage">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>

        <div class="modal-body">

            <form method="POST" action="{{ route('executive.homepages.store', 'descpackage') }}">

                @csrf

                <div class="form-group">

                    <label>Deskripsi Package</label>

                    <textarea name="content" rows="4" class="form-control" required></textarea>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" id="btnCloseCreateDescPackage2">

                        Batal

                    </button>

                    <button class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="custom-modal-backdrop" id="backdropEditDescPackage"></div>
<div class="custom-modal" id="modalEditDescPackage">

    <div class="modal-content">

        <div class="modal-header">

            <h5>Edit Deskripsi Package</h5>

            <button type="button" class="btn btn-secondary" id="btnCloseEditDescPackage">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>

        <div class="modal-body">

            <form id="editDescPackageForm" method="POST"
                data-base-url="{{ url('executive/homepages/update/descpackage') }}">

                @csrf
                @method('PUT')

                <div class="form-group">

                    <label>Deskripsi Package</label>

                    <textarea id="edp-content" name="content" rows="4" class="form-control" required></textarea>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" id="btnCloseEditDescPackage2">

                        Batal

                    </button>

                    <button class="btn btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="custom-modal-backdrop" id="backdropCreatePackageLabel"></div>
<div class="custom-modal" id="modalCreatePackageLabel">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Tambah Package Label</h5>

            <button type="button" class="btn btn-secondary" id="btnCloseCreatePackageLabel">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <form method="POST" action="{{ route('executive.homepages.store', 'packagelabel') }}">
                @csrf

                <div class="form-group">
                    <label>Package Label</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnCloseCreatePackageLabel2">
                        Batal
                    </button>

                    <button class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="custom-modal-backdrop" id="backdropEditPackageLabel"></div>
<div class="custom-modal" id="modalEditPackageLabel">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Edit Package Label</h5>

            <button type="button" class="btn btn-secondary" id="btnCloseEditPackageLabel">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <form id="editPackageLabelForm" method="POST"
                data-base-url="{{ url('executive/homepages/update/packagelabel') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Package Label</label>
                    <input type="text" id="epl-name" name="name" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnCloseEditPackageLabel2">
                        Batal
                    </button>

                    <button class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
