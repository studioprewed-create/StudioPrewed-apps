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
    $internalUsers = $users->whereNotIn('role', [
        'CLIENT',
        'BRAND_PARTNERSHIP',
        'STUDIO'
    ]);

    $brandUsers = $users->whereIn('role', [
        'BRAND_PARTNERSHIP',
        'STUDIO'
    ]);

    $clientUsers = $users->where('role', 'CLIENT');
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
                                    @elseif($user->role === 'CREATIVE_DIRECTOR') role-creative-director
                                    @elseif($user->role === 'CONTENT_CREATOR') role-content_creator
                                    @elseif($user->role === 'EDITOR') role-editor
                                    @elseif($user->role === 'PHOTOGRAFER') role-photografer
                                    @elseif($user->role === 'VIDEOGRAFER') role-videografer
                                    @elseif($user->role === 'MAKE_UP') role-make_up
                                    @elseif($user->role === 'CLIENT') role-client
                                    @elseif($user->role === 'MANAGER') role-manager
                                    @elseif($user->role === 'MARKETING') role-marketing
                                    @elseif($user->role === 'ADMIN_ATTIRE') role-admin_attire
                                    @elseif($user->role === 'STYLISH') role-stylish
                                    @elseif($user->role === 'FITTER') role-fitter
                                    @elseif($user->role === 'BRAND_PARTNERSHIP') role-brand_partnership
                                    @elseif($user->role === 'STUDIO') role-studio
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
        <h3>PARTNERSHIP & BRAND</h3>

        @if($brandUsers->isEmpty())
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                Belum ada brand partnership yang terdaftar.
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
                    @foreach($brandUsers as $index => $user)
                        <tr>

                            <td>{{ $index + 1 }}</td>

                            <td>{{ $user->name }}</td>

                            <td>{{ $user->email }}</td>

                            <td>
                                <span class="role-badge
                                    @if($user->role === 'BRAND_PARTNERSHIP')
                                        role-brand_partnership
                                    @elseif($user->role === 'STUDIO')
                                        role-studio
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

                                    data-db-nama-brand="{{ $user->dataBrand?->nama_brand ?? '' }}"
                                    data-db-logo="{{ $user->dataBrand?->logo ?? '' }}"
                                    data-db-category="{{ $user->dataBrand?->category_id ?? '' }}"
                                    data-db-description="{{ $user->dataBrand?->description ?? '' }}"
                                    data-db-email="{{ $user->dataBrand?->email ?? '' }}"
                                    data-db-phone="{{ $user->dataBrand?->phone ?? '' }}"
                                    data-db-website="{{ $user->dataBrand?->website ?? '' }}"
                                    data-db-instagram="{{ $user->dataBrand?->instagram ?? '' }}"
                                    data-db-tiktok="{{ $user->dataBrand?->tiktok ?? '' }}"
                                    data-db-active="{{ $user->dataBrand?->is_active ?? 1 }}"
                                >

                                    <i class="fa fa-pen"></i>
                                    Edit

                                </button>

                                <form
                                    action="{{ route('executive.homepages.destroy', ['section' => 'user', 'id' => $user->id]) }}"
                                    method="POST"
                                    style="display:inline-block"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini?');">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger" type="submit">
                                        <i class="fa fa-trash"></i>
                                        Hapus
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

@include('OPERATIONALPAGES.FITUR.MODAL.ModalDataAkun')