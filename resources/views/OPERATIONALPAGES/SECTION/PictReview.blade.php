 <details class="acc">
      <summary>
        <i class="fa-solid fa-image"></i> Review Pictures
        <i class="fa-solid fa-chevron-right chev"></i>
      </summary>
      <div class="acc-body" id="hero">
        <form class="form-inline" action="{{ route('executive.homepages.store', ['section'=>'hero']) }}" method="POST" enctype="multipart/form-data" style="margin-bottom:12px">
          @csrf
          <input class="input" type="number" name="order" placeholder="Urutan">
          <label class="small-muted" style="display:flex;align-items:center;gap:6px">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" checked> Aktif
          </label>
          <input class="input" type="file" name="image" accept="image/*" required>
          <button class="btn btn-sm" type="submit">Tambah Hero</button>
        </form>

        <div class="row">
          @forelse($heroes as $h)
            @php $thumb = $h->image ? asset('storage/'.$h->image) : asset('asset/IMGhome/bg1.jpg'); @endphp
            <div class="col-md-3">
              <div class="block-rel" data-hero-id="{{ $h->id }}">
                <div class="admin-badge">Hero #{{ $h->id }}</div>
                <div class="img-picker" data-type="hero" data-id="{{ $h->id }}"><i class="fas fa-image"></i> Ganti</div>
                <div class="ci" style="background-image:url('{{ $thumb }}')"></div>

                <form class="form-inline mt-2" action="{{ route('executive.homepages.update', ['section'=>'hero','id'=>$h->id]) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <input class="input" type="number" name="order" value="{{ $h->order }}" placeholder="Urutan">
                  <label class="small-muted" style="display:flex;align-items:center;gap:6px">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" {{ $h->active ? 'checked' : '' }}> Aktif
                  </label>
                  <button class="btn btn-sm btn-warning" type="submit">Simpan</button>
                </form>

                <div class="hr"></div>
                <form action="{{ route('executive.homepages.destroy', ['section'=>'hero','id'=>$h->id]) }}" method="POST" onsubmit="return confirm('Hapus gambar hero ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
              </div>
            </div>
          @empty
            <p class="small-muted">Belum ada hero image.</p>
          @endforelse
        </div>
      </div>
    </details>
