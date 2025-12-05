    <details class="acc">
        <summary>
        <i class="fa-solid fa-photo-film"></i> Gallery
        <i class="fa-solid fa-chevron-right chev"></i>
        </summary>
        <div class="acc-body" id="gallery">
        <form method="GET" class="section-search">
            <input class="input" type="text" name="gal_q" placeholder="Cari judul / deskripsi / kategori..." value="{{ request('gal_q') }}">
            <select class="input" name="gal_status">
            <option value="all" {{ request('gal_status','all')==='all'?'selected':'' }}>Semua</option>
            <option value="1" {{ request('gal_status')==='1'?'selected':'' }}>Aktif</option>
            <option value="0" {{ request('gal_status')==='0'?'selected':'' }}>Non-aktif</option>
            </select>
            <select class="input" name="gal_sort">
            <option value="order_asc"  {{ request('gal_sort','order_asc')==='order_asc'?'selected':'' }}>Urutan ↑</option>
            <option value="order_desc" {{ request('gal_sort')==='order_desc'?'selected':'' }}>Urutan ↓</option>
            <option value="created_desc" {{ request('gal_sort')==='created_desc'?'selected':'' }}>Terbaru</option>
            <option value="created_asc"  {{ request('gal_sort')==='created_asc'?'selected':'' }}>Terlama</option>
            <option value="alpha_asc"  {{ request('gal_sort')==='alpha_asc'?'selected':'' }}>Judul A–Z</option>
            <option value="alpha_desc" {{ request('gal_sort')==='alpha_desc'?'selected':'' }}>Judul Z–A</option>
            </select>
            <button class="btn btn-sm" type="submit">Cari</button>
        </form>

        <form class="form-inline" action="{{ route('executive.homepages.store', ['section'=>'gallery']) }}" method="POST" enctype="multipart/form-data" style="margin-bottom:12px">
            @csrf
            <input class="input" type="text" name="title" placeholder="Judul (ops)">
            <input class="input" type="text" name="description" placeholder="Deskripsi (opsional)" style="flex:1;min-width:240px">
            <select class="input" name="category">
            <option value="">(Pilih kategori)</option>
            <option value="prewed">Prewed Session</option>
            <option value="family">Family Session</option>
            <option value="maternity">Maternity Shoot</option>
            <option value="postwedding">Post Wedding</option>
            <option value="beauty">Beauty Shoot</option>
            <option value="birthday">Birthday Session</option>
            </select>
            <input class="input" type="number" name="order" placeholder="Urutan">
            <label class="small-muted" style="display:flex;align-items:center;gap:6px">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" checked> Aktif
            </label>
            <input class="input" type="file" name="image" accept="image/*" required>
            <button class="btn btn-sm" type="submit">Tambah Gallery</button>
        </form>

        <div class="row">
            @forelse($galleries as $g)
            @php
                $thumb = $g->image ? asset('storage/'.$g->image) : asset('asset/IMGhome/bg1.jpg');
                $opts = [
                'prewed' => 'Prewed Session',
                'family' => 'Family Session',
                'maternity' => 'Maternity Shoot',
                'postwedding' => 'Post Wedding',
                'beauty' => 'Beauty Shoot',
                'birthday' => 'Birthday Session',
                ];
            @endphp
            <div class="col-md-3">
                <div class="block-rel" data-gallery-id="{{ $g->id }}">
                <div class="admin-badge">Gallery #{{ $g->id }}</div>
                <div class="img-picker" data-type="gallery" data-id="{{ $g->id }}"><i class="fas fa-image"></i> Ganti</div>
                <div class="ci" style="background-image:url('{{ $thumb }}')"></div>

                <div class="mt-2 small-muted">Klik untuk edit:</div>
                <div contenteditable="true" class="contenteditable" data-field="title" data-id="{{ $g->id }}">{{ $g->title }}</div>
                <div contenteditable="true" class="contenteditable" data-field="description" data-id="{{ $g->id }}">{{ $g->description }}</div>

                <div class="mt-2 small-muted">Kategori:</div>
                <select class="input select-category" data-id="{{ $g->id }}">
                    <option value="">(Pilih kategori)</option>
                    @foreach($opts as $val => $label)
                    <option value="{{ $val }}" {{ $g->category===$val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <form class="form-inline mt-2" action="{{ route('executive.homepages.update', ['section'=>'gallery','id'=>$g->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input class="input" type="number" name="order" value="{{ $g->order }}" placeholder="Urutan">
                    <label class="small-muted" style="display:flex;align-items:center;gap:6px">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" {{ $g->active ? 'checked' : '' }}> Aktif
                    </label>
                    <button class="btn btn-sm btn-warning" type="submit">Simpan</button>
                </form>

                <div class="hr"></div>
                <form action="{{ route('executive.homepages.destroy', ['section'=>'gallery','id'=>$g->id]) }}" method="POST" onsubmit="return confirm('Hapus item gallery ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
                </div>
            </div>
            @empty
            <p class="small-muted">Belum ada item Gallery.</p>
            @endforelse
        </div>
        </div>
    </details>
</div>