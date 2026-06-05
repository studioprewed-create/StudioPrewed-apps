@php
    $librarySections = [
        'tacpackage' => [
            'title' => 'TAC Package',
            'subtitle' => 'Terms & conditions package',
            'items' => $tacPackages,
            'field' => 'content',
            'buttonId' => 'btn-open-tacpackage-create',
            'buttonText' => 'ADD TAC',
            'editClass' => 'btn-edit-tacpackage',
            'empty' => 'Belum ada data TAC Package.',
            'confirm' => 'Yakin hapus TAC ini?',
            'icon' => 'fa-file-contract',
        ],
        'konsepattire' => [
            'title' => 'Konsep Attire',
            'subtitle' => 'Master konsep attire',
            'items' => $konsepAttires,
            'field' => 'content',
            'buttonId' => 'btn-open-konsepattire-create',
            'buttonText' => 'ADD KONSEP ATTIRE',
            'editClass' => 'btn-edit-konsepattire',
            'empty' => 'Belum ada data Konsep Attire.',
            'confirm' => 'Yakin hapus Konsep Attire ini?',
            'icon' => 'fa-shirt',
        ],
        'descpackage' => [
            'title' => 'Deskripsi Package',
            'subtitle' => 'Master deskripsi package',
            'items' => $descPackages,
            'field' => 'content',
            'buttonId' => 'btn-open-descpackage-create',
            'buttonText' => 'ADD DESKRIPSI',
            'editClass' => 'btn-edit-descpackage',
            'empty' => 'Belum ada data Deskripsi Package.',
            'confirm' => 'Yakin hapus deskripsi package ini?',
            'icon' => 'fa-align-left',
        ],
        'packagelabel' => [
            'title' => 'Package Label',
            'subtitle' => 'Label untuk package',
            'items' => $packageLabels,
            'field' => 'name',
            'buttonId' => 'btn-open-packagelabel-create',
            'buttonText' => 'ADD LABEL',
            'editClass' => 'btn-edit-packagelabel',
            'empty' => 'Belum ada data Package Label.',
            'confirm' => 'Yakin hapus Package Label ini?',
            'icon' => 'fa-tags',
        ],
    ];
@endphp

<div class="stats-page library-master-page">

    <div class="stats-header">
        <div>
            <h1>Library Catalogue</h1>
            <p>Kelola master data package Studio Prewed</p>
        </div>

        <div class="stats-badge">
            <i class="fas fa-layer-group"></i>
            Catalogue Library
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i>
            <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="stats-grid-card library-switch-grid">
        @foreach ($librarySections as $key => $section)
            <button
                type="button"
                class="stats-card-mini library-switch-card {{ $loop->first ? 'active' : '' }}"
                data-library-target="{{ $key }}">

                <span>
                    <i class="fas {{ $section['icon'] }}"></i>
                    {{ $section['title'] }}
                </span>

                <h2>{{ $section['items']->count() }}</h2>

                <p>{{ $section['subtitle'] }}</p>
            </button>
        @endforeach
    </div>

    @foreach ($librarySections as $key => $section)
        <div
            id="{{ $key }}Section"
            class="stats-box library-panel {{ $loop->first ? '' : 'hidden' }}"
            data-library-panel="{{ $key }}">

            <div class="box-header">
                <div>
                    <h3>{{ $section['title'] }}</h3>
                    <p>Total {{ $section['items']->count() }} data tersimpan</p>
                </div>

                <button class="btn btn-primary" id="{{ $section['buttonId'] }}">
                    <i class="fa fa-plus"></i>
                    {{ $section['buttonText'] }}
                </button>
            </div>

            <div class="stats-table-wrap">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Data</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($section['items'] as $index => $item)
                            @php
                                $value = $item->{$section['field']};
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>{{ $value }}</td>

                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-secondary {{ $section['editClass'] }}"
                                        data-id="{{ $item->id }}"
                                        data-content="{{ $value }}"
                                        data-name="{{ $value }}">
                                        <i class="fa fa-pen"></i>
                                        Edit
                                    </button>
                                </td>

                                <td>
                                    <form
                                        method="POST"
                                        action="{{ route('executive.homepages.destroy', ['section' => $key, 'id' => $item->id]) }}"
                                        onsubmit="return confirm('{{ $section['confirm'] }}')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger">
                                            <i class="fa fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ $section['empty'] }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    @endforeach

</div>

@include('OPERATIONALPAGES.FITUR.MODAL.ModalCatalogue')