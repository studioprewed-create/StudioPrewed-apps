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
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-sticky-note"></i> Notes</label>
                        <textarea name="notes" class="form-control" rows="2" id="ep-notes"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-list-alt"></i> Rules</label>
                        <textarea name="rules" class="form-control" rows="2" id="ep-rules"></textarea>
                    </div>
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

<div class="custom-modal-backdrop" id="backdropDetailPackage"></div>
<div class="custom-modal" id="modalDetailPackage">

    <div class="modal-content modal-xl">

        <div class="modal-header">
            <h5>Detail Package</h5>

            <button type="button" class="btn btn-secondary" id="btnCloseDetailPackage">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-image"></i>
                    Gambar Package
                </label>

                <div class="package-detail-cover">
                    <img id="dp-image" src="">
                </div>
            </div>

            <div class="form-grid-2">

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-heading"></i>
                        Nama Package
                    </label>

                    <div class="detail-box" id="dp-nama"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tag"></i>
                        Harga
                    </label>

                    <div class="detail-box" id="dp-price"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-clock"></i>
                        Durasi
                    </label>

                    <div class="detail-box" id="dp-durasi"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-toggle-on"></i>
                        Status
                    </label>

                    <div class="detail-box" id="dp-status"></div>
                </div>

            </div>

            <div class="form-group">

                <label class="form-label">
                    <i class="fas fa-bookmark"></i>
                    Label Package
                </label>

                <div class="package-themes" id="dp-labels"></div>

            </div>

            <div class="form-group">

                <label class="form-label">
                    <i class="fas fa-palette"></i>
                    Konsep
                </label>

                <div class="package-themes" id="dp-konsep"></div>

            </div>

            <div class="form-group">

                <label class="form-label">
                    <i class="fas fa-align-left"></i>
                    Deskripsi
                </label>

                <div class="detail-list" id="dp-descriptions"></div>

            </div>

            <div class="form-group">

                <label class="form-label">
                    <i class="fas fa-sticky-note"></i>
                    Notes
                </label>

                <div class="detail-box" id="dp-notes"></div>

            </div>

            <div class="form-group">

                <label class="form-label">
                    <i class="fas fa-list-check"></i>
                    Rules
                </label>

                <div class="detail-box" id="dp-rules"></div>

            </div>

            <div class="form-group">

                <label class="form-label">
                    <i class="fas fa-file-contract"></i>
                    TAC Package
                </label>

                <div class="detail-list" id="dp-tacs"></div>

            </div>

            <div class="form-group">

                <label class="form-label">
                    <i class="fas fa-tshirt"></i>
                    Tema Baju
                </label>

                <div class="package-theme-preview-grid" id="dp-temas"></div>

            </div>

        </div>

    </div>

</div>

{{-- ===================  MODAL: CREATE TEMA BAJU  =================== --}}
<div class="custom-modal-backdrop" id="backdropCreateTema"></div>
<div class="custom-modal" id="modalCreateTema" aria-hidden="true">
    <div class="modal-content modal-xl">
        <div class="modal-header">
            <h5>Tambah Attire</h5>

            <button type="button"
                class="btn btn-secondary"
                id="btnCloseCreateTema">

                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <form action="{{ route('executive.homepages.store', ['section' => 'temabaju']) }}"
                method="POST"
                enctype="multipart/form-data"
                id="formCreateTema">

                @csrf

                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Nama Attire</label>

                        <input type="text"
                            name="nama"
                            class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Kode Attire</label>

                        <select name="attire_code_id"
                            id="create-attire-code"
                            class="form-control"
                            required>

                            <option value="">
                                Pilih kode
                            </option>

                            @foreach($attireCodes->where('active', true) as $code)
                                <option value="{{ $code->id }}"
                                    data-preview="{{ $code->next_code_preview }}">

                                    {{ $code->name }}
                                    — {{ $code->next_code_preview }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Preview Kode</label>

                        <input type="text"
                            id="create-attire-code-preview"
                            class="form-control"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label>Designer</label>

                        <select name="data_brand_id"
                            class="form-control"
                            required>

                            <option value="">
                                Pilih designer
                            </option>

                            @foreach($attireBrands as $brand)
                                <option value="{{ $brand->id }}">
                                    {{ $brand->nama_brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tipe/Konsep</label>

                        <select name="konsep_attire_id"
                            class="form-control"
                            required>

                            <option value="">
                                Pilih konsep
                            </option>

                            @foreach($konsepAttires->where('active', true) as $konsep)
                                <option value="{{ $konsep->id }}">
                                    {{ $konsep->content }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>

                        <input type="number"
                            name="harga"
                            class="form-control"
                            min="0"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Warna</label>

                        <input type="text"
                            name="warna"
                            class="form-control"
                            placeholder="Maroon, Gold, Hitam">
                    </div>

                    <div class="form-group">
                        <label>Status</label>

                        <select name="status"
                            class="form-control"
                            required>

                            <option value="ready">
                                Good / Ready
                            </option>

                            <option value="maintenance">
                                Cleaning / Repair
                            </option>

                            <option value="booked">
                                Booked
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ukuran Perempuan</label>

                        <textarea name="ukuran_wanita"
                            class="form-control"
                            rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Ukuran Laki-laki</label>

                        <textarea name="ukuran_pria"
                            class="form-control"
                            rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Order</label>

                        <input type="number"
                            name="order"
                            class="form-control"
                            min="1">
                    </div>
                </div>

                <div class="form-group">
                    <label>Catalogue Label</label>

                    <div id="create-attire-labels">
                        @foreach($packageLabels->where('active', true) as $label)
                            <label>
                                <input type="checkbox"
                                    name="label_ids[]"
                                    value="{{ $label->id }}">

                                {{ $label->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>Detail Attire</label>

                    <div id="create-attire-details"></div>

                    <button type="button"
                        class="btn btn-secondary"
                        id="btn-add-create-detail-wanita">

                        + Poin Perempuan
                    </button>

                    <button type="button"
                        class="btn btn-secondary"
                        id="btn-add-create-detail-pria">

                        + Poin Laki-laki
                    </button>

                    <button type="button"
                        class="btn btn-secondary"
                        id="btn-add-create-detail-umum">

                        + Poin Umum
                    </button>
                </div>

                <div class="form-group">
                    <label>Gambar Attire</label>

                    <div class="image-upload-container"
                        id="uploadDrop">

                        <i class="fas fa-cloud-upload-alt"></i>

                        <p>Klik atau seret gambar ke sini</p>

                        <small>
                            JPG, PNG, WEBP | Maksimal 2MB
                        </small>

                        <input type="file"
                            name="images[]"
                            id="inputImages"
                            accept="image/jpeg,image/png,image/webp"
                            multiple
                            class="file-overlay"
                            required>
                    </div>

                    <div id="previewImages"
                        class="thumbs-wrap"></div>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox"
                            name="active"
                            value="1"
                            checked>

                        Active
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        id="btnCloseCreateTema2">

                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-primary">

                        <i class="fa-solid fa-save"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="custom-modal-backdrop" id="backdropEditTema"></div>
<div class="custom-modal" id="modalEditTema" aria-hidden="true">
    <div class="modal-content modal-xl">
        <div class="modal-header">
            <h5>Edit Attire</h5>

            <button type="button"
                class="btn btn-secondary"
                id="btnCloseEditTema">

                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <form method="POST"
                enctype="multipart/form-data"
                id="editTemaForm"
                data-base-url="{{ url('/executive/tema-baju') }}">

                @csrf
                @method('PUT')

                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Nama Attire</label>

                        <input type="text"
                            name="nama"
                            id="et-nama"
                            class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Kode Attire</label>

                        <input type="text"
                            id="et-kode"
                            class="form-control"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label>Designer</label>

                        <select name="data_brand_id"
                            id="et-data-brand-id"
                            class="form-control"
                            required>

                            @foreach($attireBrands as $brand)
                                <option value="{{ $brand->id }}">
                                    {{ $brand->nama_brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tipe/Konsep</label>

                        <select name="konsep_attire_id"
                            id="et-konsep-attire-id"
                            class="form-control"
                            required>

                            @foreach($konsepAttires->where('active', true) as $konsep)
                                <option value="{{ $konsep->id }}">
                                    {{ $konsep->content }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>

                        <input type="number"
                            name="harga"
                            id="et-harga"
                            class="form-control"
                            min="0"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Warna</label>

                        <input type="text"
                            name="warna"
                            id="et-warna"
                            class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Status</label>

                        <select name="status"
                            id="et-status"
                            class="form-control"
                            required>

                            <option value="ready">
                                Good / Ready
                            </option>

                            <option value="maintenance">
                                Cleaning / Repair
                            </option>

                            <option value="booked">
                                Booked
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Order</label>

                        <input type="number"
                            name="order"
                            id="et-order"
                            class="form-control"
                            min="1">
                    </div>

                    <div class="form-group">
                        <label>Ukuran Perempuan</label>

                        <textarea name="ukuran_wanita"
                            id="et-ukuran-wanita"
                            class="form-control"
                            rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Ukuran Laki-laki</label>

                        <textarea name="ukuran_pria"
                            id="et-ukuran-pria"
                            class="form-control"
                            rows="3"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label>Catalogue Label</label>

                    <div id="edit-attire-labels">
                        @foreach($packageLabels->where('active', true) as $label)
                            <label>
                                <input type="checkbox"
                                    name="label_ids[]"
                                    value="{{ $label->id }}">

                                {{ $label->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>Detail Attire</label>

                    <div id="edit-attire-details"></div>

                    <button type="button"
                        class="btn btn-secondary"
                        id="btn-add-edit-detail-wanita">

                        + Poin Perempuan
                    </button>

                    <button type="button"
                        class="btn btn-secondary"
                        id="btn-add-edit-detail-pria">

                        + Poin Laki-laki
                    </button>

                    <button type="button"
                        class="btn btn-secondary"
                        id="btn-add-edit-detail-umum">

                        + Poin Umum
                    </button>
                </div>

                <div class="form-group">
                    <label>Ganti Gambar</label>

                    <div class="image-upload-container"
                        id="uploadDropTemaEdit">

                        <i class="fas fa-cloud-upload-alt"></i>

                        <p>
                            Pilih gambar baru untuk mengganti semua gambar lama
                        </p>

                        <input type="file"
                            name="images[]"
                            id="et-images"
                            accept="image/jpeg,image/png,image/webp"
                            multiple
                            class="file-overlay">
                    </div>

                    <div id="previewImagesEdit"
                        class="thumbs-wrap"></div>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox"
                            name="active"
                            value="1"
                            id="et-active">

                        Active
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        id="btnCloseEditTema2">

                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-primary">

                        <i class="fa-solid fa-save"></i>
                        Update
                    </button>
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

<div class="custom-modal-backdrop"id="backdropCreateAttireCode"></div>
<div class="custom-modal" id="modalCreateAttireCode" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Tambah Kode Attire</h5>

            <button type="button"
                class="btn btn-secondary"
                id="btnCloseCreateAttireCode">

                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <form method="POST"
                action="{{ route('executive.homepages.store', 'attirecode') }}">

                @csrf

                <div class="form-group">
                    <label class="form-label">
                        Nama Kode
                    </label>

                    <input type="text"
                        name="name"
                        class="form-control"
                        placeholder="Sunda Jawa"
                        value="{{ old('name') }}"
                        required>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">
                            Prefix
                        </label>

                        <input type="text"
                            name="prefix"
                            id="create-attirecode-prefix"
                            class="form-control"
                            placeholder="SJW"
                            maxlength="20"
                            value="{{ old('prefix') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Pemisah
                        </label>

                        <input type="text"
                            name="separator"
                            class="form-control"
                            maxlength="3"
                            value="{{ old('separator', '-') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Jumlah Digit
                        </label>

                        <input type="number"
                            name="digit_length"
                            class="form-control"
                            min="1"
                            max="6"
                            value="{{ old('digit_length', 2) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Order
                        </label>

                        <input type="number"
                            name="order"
                            class="form-control"
                            min="1"
                            value="{{ old('order') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox"
                            name="active"
                            value="1"
                            checked>

                        Active
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        id="btnCloseCreateAttireCode2">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-primary">
                        <i class="fa-solid fa-save"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="custom-modal-backdrop"id="backdropEditAttireCode"></div>
<div class="custom-modal" id="modalEditAttireCode" aria-hidden="true">

    <div class="modal-content">
        <div class="modal-header">
            <h5>Edit Kode Attire</h5>

            <button type="button"
                class="btn btn-secondary"
                id="btnCloseEditAttireCode">

                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <form method="POST"
                id="editAttireCodeForm"
                data-base-url="{{ url('executive/homepages/update/attirecode') }}">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">
                        Nama Kode
                    </label>

                    <input type="text"
                        name="name"
                        id="edit-attirecode-name"
                        class="form-control"
                        required>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">
                            Prefix
                        </label>

                        <input type="text"
                            name="prefix"
                            id="edit-attirecode-prefix"
                            class="form-control"
                            maxlength="20"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Pemisah
                        </label>

                        <input type="text"
                            name="separator"
                            id="edit-attirecode-separator"
                            class="form-control"
                            maxlength="3">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Jumlah Digit
                        </label>

                        <input type="number"
                            name="digit_length"
                            id="edit-attirecode-digit"
                            class="form-control"
                            min="1"
                            max="6"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Order
                        </label>

                        <input type="number"
                            name="order"
                            id="edit-attirecode-order"
                            class="form-control"
                            min="1">
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox"
                            name="active"
                            value="1"
                            id="edit-attirecode-active">

                        Active
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        id="btnCloseEditAttireCode2">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-primary">
                        <i class="fa-solid fa-save"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>