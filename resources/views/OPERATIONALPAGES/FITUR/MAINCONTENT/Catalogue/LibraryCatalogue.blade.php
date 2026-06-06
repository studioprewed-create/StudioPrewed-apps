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

    @include('OPERATIONALPAGES.FITUR.Notifikasi.Alert')

    <div class="library-summary-grid">
        @foreach ($librarySections as $key => $section)
            <button type="button" class="library-summary-card {{ $loop->first ? 'active' : '' }}"
                data-library-target="{{ $key }}">
                <div class="summary-icon">
                    <i class="fas {{ $section['icon'] }}"></i>
                </div>

                <div class="summary-content">
                    <span>{{ $section['title'] }}</span>
                    <h2>{{ $section['items']->count() }}</h2>
                    <p>{{ $section['subtitle'] }}</p>
                </div>
            </button>
        @endforeach
    </div>

    @foreach ($librarySections as $key => $section)
        <section id="{{ $key }}Section" class="library-panel {{ $loop->first ? '' : 'hidden' }}"
            data-library-panel="{{ $key }}">

            <div class="library-panel-header">
                <div>
                    <span>Master Data</span>
                    <h3>{{ $section['title'] }}</h3>
                    <p>Total {{ $section['items']->count() }} data tersimpan</p>
                </div>

                <button class="btn btn-primary" id="{{ $section['buttonId'] }}">
                    <i class="fa fa-plus"></i>
                    {{ $section['buttonText'] }}
                </button>
            </div>

            <div class="library-table-wrap">
                <table class="library-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th class="text-center">Edit</th>
                            <th class="text-center">Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($section['items'] as $index => $item)
                            @php
                                $value = $item->{$section['field']};
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="library-data-text">
                                        {{ $value }}
                                    </div>
                                </td>

                                <td>
                                    <span class="library-status {{ $item->active ? 'is-active' : 'is-inactive' }}">
                                        {{ $item->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-secondary {{ $section['editClass'] }}"
                                        data-id="{{ $item->id }}" data-content="{{ $value }}"
                                        data-name="{{ $value }}">
                                        <i class="fa fa-pen"></i>
                                        Edit
                                    </button>
                                </td>

                                <td class="text-center">
                                    <form method="POST"
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
                                <td colspan="5">
                                    <div class="library-empty">
                                        <i class="fas fa-folder-open"></i>
                                        {{ $section['empty'] }}
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endforeach
</div>

@include('OPERATIONALPAGES.FITUR.MODAL.ModalCatalogue')
