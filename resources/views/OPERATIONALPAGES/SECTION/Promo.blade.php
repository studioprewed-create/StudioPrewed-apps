<details class="acc">
      <summary>
        <i class="fa-solid fa-bullhorn"></i> Promo Banners
        <i class="fa-solid fa-chevron-right chev"></i>
      </summary>
      <div class="acc-body" id="promo">
        <form class="form-inline" method="GET" style="margin-bottom:10px">
          <select class="input" name="promo_status">
            <option value="all" {{ request('promo_status','all')==='all'?'selected':'' }}>Semua</option>
            <option value="1" {{ request('promo_status')==='1'?'selected':'' }}>Aktif</option>
            <option value="0" {{ request('promo_status')==='0'?'selected':'' }}>Non-aktif</option>
          </select>
          <select class="input" name="promo_sort">
            <option value="order_asc"  {{ request('promo_sort','order_asc')==='order_asc'?'selected':'' }}>Urutan ↑</option>
            <option value="order_desc" {{ request('promo_sort')==='order_desc'?'selected':'' }}>Urutan ↓</option>
            <option value="created_desc" {{ request('promo_sort')==='created_desc'?'selected':'' }}>Terbaru</option>
            <option value="created_asc"  {{ request('promo_sort')==='created_asc'?'selected':'' }}>Terlama</option>
          </select>
          <button class="btn btn-sm">Terapkan</button>
        </form>

        <form class="form-inline" action="{{ route('executive.homepages.store', ['section'=>'promo']) }}" method="POST" enctype="multipart/form-data" style="margin-bottom:12px">
          @csrf
          <input class="input" type="number" name="order" placeholder="Urutan">
          <label class="small-muted" style="display:flex;align-items:center;gap:6px">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" checked> Aktif
          </label>
          <input class="input" type="file" name="image" accept="image/*" required>
          <button class="btn btn-sm" type="submit">Tambah Promo</button>
        </form>

        <div class="row">
          @forelse($promos as $p)
            @php $thumb = $p->image ? asset('storage/'.$p->image) : asset('asset/IMGhome/bg1.jpg'); @endphp
            <div class="col-md-3">
              <div class="block-rel" data-promo-id="{{ $p->id }}">
                <div class="admin-badge">Promo #{{ $p->id }}</div>
                <div class="img-picker" data-type="promo" data-id="{{ $p->id }}"><i class="fas fa-image"></i> Ganti</div>
                <div class="ci" style="background-image:url('{{ $thumb }}')"></div>

                <form class="form-inline mt-2" action="{{ route('executive.homepages.update', ['section'=>'promo','id'=>$p->id]) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <input class="input" type="number" name="order" value="{{ $p->order }}" placeholder="Urutan">
                  <label class="small-muted" style="display:flex;align-items:center;gap:6px">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" {{ $p->active ? 'checked' : '' }}> Aktif
                  </label>
                  <button class="btn btn-sm btn-warning" type="submit">Simpan</button>
                </form>

                <div class="hr"></div>
                <form action="{{ route('executive.homepages.destroy', ['section'=>'promo','id'=>$p->id]) }}" method="POST" onsubmit="return confirm('Hapus promo ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
              </div>
            </div>
          @empty
            <p class="small-muted">Belum ada Promo Banner.</p>
          @endforelse
        </div>
      </div>
    </details>