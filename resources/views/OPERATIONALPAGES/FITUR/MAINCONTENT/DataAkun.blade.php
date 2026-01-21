<div class="page-header">
    <div>
        <h1>MANAGEMENT ACCOUNT</h1>
    </div>
    <div class="header-actions">
        <button class="btn btn-primary" id="btn-open-create">
            <i class="fa fa-plus"></i>
            ADD USER
        </button>
    </div>
</div>

{{-- Alert sukses & error --}}
@if(session('success'))
    <div class="alert alert-success">
        <i class="fa fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-triangle"></i>
        <ul style="margin: 0; padding-left: 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $internalUsers = $users->where('role', '!=', 'CLIENT');
    $clientUsers   = $users->where('role', 'CLIENT');
@endphp

<div class="tables">
    <div>
        <h3>EXECUTIVE & TEAMTIVE</h3>

        @if($internalUsers->isEmpty())
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                Belum ada user internal yang terdaftar.
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($internalUsers as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge
                                    @if($user->role === 'ADMIN') role-admin
                                    @elseif($user->role === 'DIREKTUR') role-direktur
                                    @elseif($user->role === 'ADMIN_EDITOR') role-admin_editor
                                    @elseif($user->role === 'ATTIRE') role-attire
                                    @elseif($user->role === 'EDITOR') role-editor
                                    @elseif($user->role === 'PHOTOGRAFER') role-photografer
                                    @elseif($user->role === 'VIDEOGRAFER') role-videografer
                                    @elseif($user->role === 'MAKE_UP') role-make_up
                                    @elseif($user->role === 'CLIENT') role-client
                                    @endif">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-secondary btn-edit-user"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ $user->role }}"

                                    data-ddk-nama="{{ $user->dataDiriKaryawan?->nama_lengkap ?? '' }}"
                                    data-ddk-tempat-lahir="{{ $user->dataDiriKaryawan?->tempat_lahir ?? '' }}"
                                    data-ddk-tanggal-lahir="{{ $user->dataDiriKaryawan?->tanggal_lahir?->format('Y-m-d') ?? '' }}"
                                    data-ddk-jk="{{ $user->dataDiriKaryawan?->jenis_kelamin ?? '' }}"
                                    data-ddk-status-nikah="{{ $user->dataDiriKaryawan?->status_pernikahan ?? '' }}"
                                    data-ddk-kewarganegaraan="{{ $user->dataDiriKaryawan?->kewarganegaraan ?? '' }}"
                                    data-ddk-alamat="{{ $user->dataDiriKaryawan?->alamat ?? '' }}"
                                    data-ddk-no-hp="{{ $user->dataDiriKaryawan?->no_hp ?? '' }}"
                                    data-ddk-status-karyawan="{{ $user->dataDiriKaryawan?->status_karyawan ?? '' }}"
                                    data-ddk-tanggal-masuk="{{ $user->dataDiriKaryawan?->tanggal_masuk?->format('Y-m-d') ?? '' }}"
                                    data-ddk-tanggal-keluar="{{ $user->dataDiriKaryawan?->tanggal_keluar?->format('Y-m-d') ?? '' }}"
                                >
                                    <i class="fa fa-pen"></i> Edit
                                </button>

                                <form action="{{ route('executive.homepages.destroy', ['section' => 'user', 'id' => $user->id]) }}"
                                      method="POST"
                                      style="display:inline-block"
                                      onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div>
        <h3>CLIENT</h3>

        @if($clientUsers->isEmpty())
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                Belum ada client yang terdaftar.
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientUsers as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge role-client">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-secondary btn-edit-user"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ $user->role }}"

                                    data-dd-nama="{{ $user->dataDiri?->nama ?? '' }}"
                                    data-dd-phone="{{ $user->dataDiri?->phone ?? '' }}"
                                    data-dd-jk="{{ $user->dataDiri?->jenis_kelamin ?? '' }}"
                                    data-dd-tgl-lahir="{{ $user->dataDiri?->tanggal_lahir?->format('Y-m-d') ?? '' }}"
                                    data-dd-tgl-nikah="{{ $user->dataDiri?->tanggal_pernikahan?->format('Y-m-d') ?? '' }}"
                                    data-dd-nama-pasangan="{{ $user->dataDiri?->nama_pasangan ?? '' }}"
                                    data-dd-phone-pasangan="{{ $user->dataDiri?->phone_pasangan ?? '' }}"
                                    data-dd-jk-pasangan="{{ $user->dataDiri?->jenis_kelamin_pasangan ?? '' }}"
                                    data-dd-tgl-lahir-pasangan="{{ $user->dataDiri?->tanggal_lahir_pasangan?->format('Y-m-d') ?? '' }}"
                                >
                                    <i class="fa fa-pen"></i> Edit
                                </button>

                                <form action="{{ route('executive.homepages.destroy', ['section' => 'user', 'id' => $user->id]) }}"
                                      method="POST"
                                      style="display:inline-block"
                                      onsubmit="return confirm('Yakin ingin menghapus client ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

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
                                <option value="ADMIN_EDITOR">ADMIN_EDITOR</option>
                            </optgroup>

                            <optgroup label="Teamtive">
                                <option value="ATTIRE">ATTIRE</option>
                                <option value="EDITOR">EDITOR</option>
                                <option value="PHOTOGRAFER">PHOTOGRAFER</option>
                                <option value="VIDEOGRAFER">VIDEOGRAFER</option>
                                <option value="MAKE_UP">MAKE_UP</option>
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

            <form id="editUserForm" method="POST"
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
                                <option value="ADMIN_EDITOR">ADMIN_EDITOR</option>
                            </optgroup>
                            <optgroup label="Teamtive">
                                <option value="ATTIRE">ATTIRE</option>
                                <option value="EDITOR">EDITOR</option>
                                <option value="PHOTOGRAFER">PHOTOGRAFER</option>
                                <option value="VIDEOGRAFER">VIDEOGRAFER</option>
                                <option value="MAKE_UP">MAKE_UP</option>
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
                        <hr>
                        <h6>Data Diri Client</h6>

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

                        <hr>
                        <h6>Data Pasangan</h6>

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

                    {{-- ================================================= --}}
                    {{-- ============== DATA DIRI KARYAWAN ================ --}}
                    {{-- ================================================= --}}
                    <div id="form-karyawan" style="display:none;">
                        <hr>
                        <h6>Data Diri Karyawan</h6>

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