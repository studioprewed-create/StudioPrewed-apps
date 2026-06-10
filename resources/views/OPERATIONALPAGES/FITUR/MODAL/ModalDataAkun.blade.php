
<div id="modal-backdrop" class="custom-modal-backdrop"></div>

    <div id="modal-create" class="custom-modal">
        <div class="modal modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="btn btn-secondary" data-close-modal>&times;</button>
            </div>

        <form method="POST" action="{{ route('executive.homepages.store', 'user') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required placeholder="Nama lengkap">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="email@example.com">
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <optgroup label="Executive">
                                <option value="DIREKTUR">DIREKTUR</option>
                                <option value="ADMIN">ADMIN</option>
                                <option value="MANAGER">MANAGER</option>
                                <option value="CREATIVE_DIRECTOR">CREATIVE_DIRECTOR</option>
                            </optgroup>

                            <optgroup label="Teamtive">
                                <option value="EDITOR">EDITOR</option>
                                <option value="PHOTOGRAFER">PHOTOGRAFER</option>
                                <option value="VIDEOGRAFER">VIDEOGRAFER</option>
                                <option value="MAKE_UP">MAKE_UP</option>
                            </optgroup>

                            <optgroup label="Creative">
                                <option value="MARKETING">MARKETING</option>
                                <option value="CONTENT_CREATOR">CONTENT_CREATOR</option>
                                <option value="ADMIN_ATTIRE">ADMIN_ATTIRE</option>
                                <option value="STYLISH">STYLISH</option>
                                <option value="FITTER">FITTER</option>
                            </optgroup>

                            <optgroup label="Partnership">
                                <option value="BRAND_PARTNERSHIP">BRAND_PARTNERSHIP</option>
                                <option value="STUDIO">STUDIO</option>  
                            </optgroup>

                            <optgroup label="Client">
                                <option value="CLIENT">CLIENT</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-edit" class="custom-modal">
        <div class="modal modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn btn-secondary" data-close-modal>&times;</button>
            </div>

            <form id="editUserForm" method="POST" enctype="multipart/form-data"
                data-base-url="{{ url('executive/homepages/update/user') }}">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    {{-- ================= USER UTAMA ================= --}}
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" id="edit-name"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="edit-email"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" id="edit-role"
                                class="form-control" required>
                            <optgroup label="Executive">
                                <option value="DIREKTUR">DIREKTUR</option>
                                <option value="ADMIN">ADMIN</option>
                                <option value="MANAGER">MANAGER</option>
                                <option value="CREATIVE_DIRECTOR">CREATIVE_DIRECTOR</option>
                            </optgroup>

                            <optgroup label="Teamtive">
                                <option value="EDITOR">EDITOR</option>
                                <option value="PHOTOGRAFER">PHOTOGRAFER</option>
                                <option value="VIDEOGRAFER">VIDEOGRAFER</option>
                                <option value="MAKE_UP">MAKE_UP</option>
                            </optgroup>

                            <optgroup label="Creative">
                                <option value="MARKETING">MARKETING</option>
                                <option value="CONTENT_CREATOR">CONTENT_CREATOR</option>
                                <option value="ADMIN_ATTIRE">ADMIN_ATTIRE</option>
                                <option value="STYLISH">STYLISH</option>
                                <option value="FITTER">FITTER</option>
                            </optgroup>

                            <optgroup label="Partnership">
                                <option value="BRAND_PARTNERSHIP">BRAND_PARTNERSHIP</option>
                                <option value="STUDIO">STUDIO</option>  
                            </optgroup>

                            <optgroup label="Client">
                                <option value="CLIENT">CLIENT</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Password (opsional)</label>
                        <input type="password" name="password"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="form-control">
                    </div>

                    {{-- ================================================= --}}
                    {{-- ================= DATA DIRI CLIENT =============== --}}
                    {{-- ================================================= --}}
                    <div id="form-client" style="display:none;">

                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" id="dd-nama"
                                name="data_diri[nama]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>No HP</label>
                            <input type="text" id="dd-phone"
                                name="data_diri[phone]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Jenis Kelamin</label>
                            <select id="dd-jk"
                                    name="data_diri[jenis_kelamin]"
                                    class="form-control">
                                <option value="">- Pilih -</option>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" id="dd-tgl-lahir"
                                name="data_diri[tanggal_lahir]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Pernikahan</label>
                            <input type="date" id="dd-tgl-nikah"
                                name="data_diri[tanggal_pernikahan]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Nama Pasangan</label>
                            <input type="text" id="dd-nama-pasangan"
                                name="data_diri[nama_pasangan]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>No HP Pasangan</label>
                            <input type="text" id="dd-phone-pasangan"
                                name="data_diri[phone_pasangan]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Jenis Kelamin Pasangan</label>
                            <select id="dd-jk-pasangan"
                                    name="data_diri[jenis_kelamin_pasangan]"
                                    class="form-control">
                                <option value="">- Pilih -</option>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Lahir Pasangan</label>
                            <input type="date" id="dd-tgl-lahir-pasangan"
                                name="data_diri[tanggal_lahir_pasangan]"
                                class="form-control">
                        </div>
                    </div>

                    <div id="form-brand" style="display:none;">

                        <div class="mb-3">
                            <label>Nama Brand</label>
                            <input type="text"
                                id="db-nama-brand"
                                name="data_brand[nama_brand]"
                                class="form-control">
                        </div>
                        <div class="mb-3">

                            <label>Logo Brand</label>

                            <input type="file"
                                id="db-logo"
                                name="data_brand[logo]"
                                class="form-control"
                                accept="image/*">

                        </div>

                        <div class="mb-3" id="db-logo-preview-wrap" style="display:none;">

                            <img id="db-logo-preview"
                                src=""
                                alt="Logo Preview"
                                style="
                                    width:100px;
                                    height:100px;
                                    object-fit:cover;
                                    border-radius:10px;
                                    border:1px solid #ddd;
                                ">
                        </div>

                        <div class="mb-3">
                            <label>Kategori Brand</label>

                            <select id="db-category"
                                    name="data_brand[category_id]"
                                    class="form-control">

                                <option value="">- Pilih Category -</option>

                                @foreach ($brandCategories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Description</label>

                            <textarea id="db-description"
                                    name="data_brand[description]"
                                    class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>

                            <input type="email"
                                id="db-email"
                                name="data_brand[email]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Phone</label>

                            <input type="text"
                                id="db-phone"
                                name="data_brand[phone]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Website</label>

                            <input type="text"
                                id="db-website"
                                name="data_brand[website]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Instagram</label>

                            <input type="text"
                                id="db-instagram"
                                name="data_brand[instagram]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Tiktok</label>

                            <input type="text"
                                id="db-tiktok"
                                name="data_brand[tiktok]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Status</label>

                            <select id="db-active"
                                    name="data_brand[is_active]"
                                    class="form-control">

                                <option value="1">Active</option>
                                <option value="0">Non Active</option>

                            </select>
                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- ============== DATA DIRI KARYAWAN ================ --}}
                    {{-- ================================================= --}}
                    <div id="form-karyawan" style="display:none;">

                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" id="ddk-nama"
                                name="data_diri_karyawan[nama_lengkap]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Tempat Lahir</label>
                            <input type="text" id="ddk-tempat-lahir"
                                name="data_diri_karyawan[tempat_lahir]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" id="ddk-tanggal-lahir"
                                name="data_diri_karyawan[tanggal_lahir]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Jenis Kelamin</label>
                            <select id="ddk-jk"
                                    name="data_diri_karyawan[jenis_kelamin]"
                                    class="form-control">
                                <option value="">- Pilih -</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Status Pernikahan</label>
                            <select id="ddk-status-nikah"
                                    name="data_diri_karyawan[status_pernikahan]"
                                    class="form-control">
                                <option value="">- Pilih -</option>
                                <option value="Lajang">Lajang</option>
                                <option value="Menikah">Menikah</option>
                                <option value="Cerai">Cerai</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Kewarganegaraan</label>
                            <input type="text" id="ddk-kewarganegaraan"
                                name="data_diri_karyawan[kewarganegaraan]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea id="ddk-alamat"
                                    name="data_diri_karyawan[alamat]"
                                    class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>No HP</label>
                            <input type="text" id="ddk-no-hp"
                                name="data_diri_karyawan[no_hp]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Status Karyawan</label>
                            <select id="ddk-status-karyawan"
                                    name="data_diri_karyawan[status_karyawan]"
                                    class="form-control">
                                <option value="">- Pilih -</option>
                                <option value="Tetap">Tetap</option>
                                <option value="Kontrak">Kontrak</option>
                                <option value="Magang">Magang</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Tetap">Owner</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Masuk</label>
                            <input type="date" id="ddk-tanggal-masuk"
                                name="data_diri_karyawan[tanggal_masuk]"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Keluar</label>
                            <input type="date" id="ddk-tanggal-keluar"
                                name="data_diri_karyawan[tanggal_keluar]"
                                class="form-control">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-close-modal>Batal</button>
                    <button type="submit"
                            class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
