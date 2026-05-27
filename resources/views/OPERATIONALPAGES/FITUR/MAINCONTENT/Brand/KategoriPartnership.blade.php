<div class="page-header">
    <div>
        <h1>KATEGORI PARTNERSHIP</h1>
    </div>

    <div class="header-actions">
        <button class="btn btn-primary"
                id="btn-open-brand-category-create">
            <i class="fa fa-plus"></i>
            ADD CATEGORY
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
        <ul style="margin: 0; padding-left: 18px;">
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
                <th>Nama</th>
                <th>Description</th>
                <th width="200">Action</th>
            </tr>
        </thead>

        <tbody>

            @forelse($brandCategories as $index => $category)
                <tr>

                    <td>{{ $index + 1 }}</td>

                    <td>{{ $category->name }}</td>

                    <td>{{ $category->description }}</td>

                    <td>

                        <button
                            type="button"
                            class="btn btn-secondary btn-edit-brand-category"

                            data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}"
                            data-description="{{ $category->description }}"
                        >
                            <i class="fa fa-pen"></i>
                            Edit
                        </button>

                        <form
                            action="{{ route('executive.homepages.destroy', ['section' => 'brand-category', 'id' => $category->id]) }}"
                            method="POST"
                            style="display:inline-block;"
                            onsubmit="return confirm('Yakin hapus kategori ini?');"
                        >
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
                        Belum ada kategori partnership.
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>
</div>

<div id="modal-brand-category-create" class="custom-modal">

    <div class="modal modal-content">

        <div class="modal-header">
            <h5>Tambah Kategori Partnership</h5>

            <button
                type="button"
                class="btn btn-secondary"
                data-close-brand-category-modal
            >
                &times;
            </button>
        </div>

        <form
            method="POST"
            action="{{ route('executive.homepages.store', 'brand-category') }}"
        >
            @csrf

            <div class="modal-body">

                <div class="mb-3">
                    <label>Nama Kategori</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label>Description</label>

                    <textarea
                        name="description"
                        class="form-control"
                    ></textarea>
                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-close-brand-category-modal
                >
                    Batal
                </button>

                <button class="btn btn-primary">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

<div id="modal-backdrop" class="custom-modal-backdrop"></div>

<div id="modal-brand-category-edit" class="custom-modal">

    <div class="modal modal-content">

        <div class="modal-header">
            <h5>Edit Kategori Partnership</h5>

            <button
                type="button"
                class="btn btn-secondary"
                data-close-brand-category-modal
            >
                &times;
            </button>
        </div>

        <form
            id="editBrandCategoryForm"
            method="POST"
            data-base-url="{{ url('executive/homepages/update/brand-category') }}"
        >

            @csrf
            @method('PUT')

            <div class="modal-body">

                <div class="mb-3">
                    <label>Nama Kategori</label>

                    <input
                        type="text"
                        id="edit-brand-category-name"
                        name="name"
                        class="form-control"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label>Description</label>

                    <textarea
                        id="edit-brand-category-description"
                        name="description"
                        class="form-control"
                    ></textarea>
                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-close-brand-category-modal
                >
                    Batal
                </button>

                <button class="btn btn-primary">
                    Update
                </button>

            </div>

        </form>

    </div>

</div>

