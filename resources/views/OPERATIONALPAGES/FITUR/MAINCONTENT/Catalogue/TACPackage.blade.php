<div class="page-header">
    <div>
        <h1>TAC PACKAGE</h1>
    </div>

    <div class="header-actions">
        <button
            class="btn btn-primary"
            id="btn-open-tacpackage-create">
            <i class="fa fa-plus"></i>
            ADD TAC
        </button>
    </div>

    <div class="header-actions">
        <button
            class="btn btn-primary"
            id="btn-open-konsepattire-create">
            <i class="fa fa-plus"></i>
            ADD KONSEP ATTIRE
        </button>
    </div>

    <div class="header-actions">
        <button
            class="btn btn-primary"
            id="btn-open-descpackage-create">

            <i class="fa fa-plus"></i>
            ADD DESKRIPSI PACKAGE

        </button>
    </div>
</div>

@if(session('success'))
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

<div class="tables">

    <table class="table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Content</th>
                <th width="180">Edit</th>
                <th width="180">Delete</th>
            </tr>
        </thead>

        <tbody>

            @forelse($tacPackages as $index => $tac)

            <tr>

                <td>{{ $index + 1 }}</td>

                <td>
                    {{ $tac->content }}
                </td>

                <td>

                    <button
                        type="button"
                        class="btn btn-secondary btn-edit-tacpackage"

                        data-id="{{ $tac->id }}"
                        data-content="{{ $tac->content }}">

                        <i class="fa fa-pen"></i>
                        Edit

                    </button>

                </td>

                <td>

                    <form
                        method="POST"
                        action="{{ route('executive.homepages.destroy', ['section' => 'tacpackage', 'id' => $tac->id]) }}"
                        onsubmit="return confirm('Yakin hapus TAC ini?')">

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
                <td colspan="4">
                    Belum ada data TAC Package.
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>

<div class="tables">

    <table class="table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Content</th>
                <th width="180">Edit</th>
                <th width="180">Delete</th>
            </tr>
        </thead>

        <tbody>

            @forelse($konsepAttires as $index => $konsep)

            <tr>

                <td>{{ $index + 1 }}</td>

                <td>
                    {{ $konsep->content }}
                </td>

                <td>

                    <button
                        type="button"
                        class="btn btn-secondary btn-edit-konsepattire"

                        data-id="{{ $konsep->id }}"
                        data-content="{{ $konsep->content }}">

                        <i class="fa fa-pen"></i>
                        Edit

                    </button>

                </td>

                <td>

                    <form
                        method="POST"
                        action="{{ route('executive.homepages.destroy', ['section' => 'konsepattire', 'id' => $konsep->id]) }}"
                        onsubmit="return confirm('Yakin hapus Konsep Attire ini?')">

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
                <td colspan="4">
                    Belum ada data Konsep Attire.
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>

<div class="tables">

    <table class="table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Content</th>
                <th width="180">Edit</th>
                <th width="180">Delete</th>
            </tr>
        </thead>

        <tbody>

            @forelse($descPackages as $index => $desc)

            <tr>

                <td>{{ $index + 1 }}</td>

                <td>{{ $desc->content }}</td>

                <td>

                    <button
                        type="button"
                        class="btn btn-secondary btn-edit-descpackage"
                        data-id="{{ $desc->id }}"
                        data-content="{{ $desc->content }}">

                        <i class="fa fa-pen"></i>
                        Edit

                    </button>

                </td>

                <td>

                    <form
                        method="POST"
                        action="{{ route('executive.homepages.destroy', ['section' => 'descpackage', 'id' => $desc->id]) }}"
                        onsubmit="return confirm('Yakin hapus deskripsi package ini?')">

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
                <td colspan="4">
                    Belum ada data Deskripsi Package.
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@include('OPERATIONALPAGES.FITUR.MODAL.ModalCatalogue')