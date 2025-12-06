 <details class="acc">
      <summary>
        <i class="fa-solid fa-comments"></i> Reviews
        <i class="fa-solid fa-chevron-right chev"></i>
      </summary>
      <div class="acc-body" id="reviews">
        <form method="GET" class="section-search">
          <input class="input" type="text" name="rev_q" placeholder="Cari nama / role / isi / tanggal..." value="{{ request('rev_q') }}">
          <button class="btn btn-sm" type="submit">Cari</button>
        </form>

        <form class="form-inline" action="{{ route('executive.homepages.store', ['section'=>'review']) }}" method="POST" enctype="multipart/form-data" style="margin-bottom:12px">
          @csrf
          <input class="input" type="text" name="name" placeholder="Nama" required>
          <input class="input" type="text" name="role" placeholder="Role (ops)">
          <input class="input" type="number" name="rating" placeholder="Rating 1-5" min="1" max="5">
          <input class="input" type="text" name="date" placeholder="Tanggal (ops)">
          <label class="small-muted" style="display:flex;align-items:center;gap:6px">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" checked> Aktif
          </label>
          <input class="input" type="file" name="avatar" accept="image/*">
          <input class="input" type="text" name="content" placeholder="Isi review (ops)" style="flex:1;min-width:240px">
          <button class="btn btn-sm" type="submit">Tambah Review</button>
        </form>

        <div class="row">
          @foreach($reviews as $r)
            <div class="col-md-3">
              <div class="block-rel" data-review-id="{{ $r->id }}">
                <div class="admin-badge">Review #{{ $r->id }}</div>
                <div class="img-picker" data-type="review" data-id="{{ $r->id }}"><i class="fas fa-image"></i> Ganti</div>
                <div style="display:flex;gap:10px;align-items:center">
                  <img src="{{ $r->avatar ? asset('public/storage/'.$r->avatar) : 'https://via.placeholder.com/80' }}" style="width:72px;height:72px;border-radius:8px;object-fit:cover">
                  <div>
                    <div contenteditable="true" class="contenteditable" data-field="name" data-id="{{ $r->id }}">{{ $r->name }}</div>
                    <div contenteditable="true" class="contenteditable" data-field="role" data-id="{{ $r->id }}">{{ $r->role }}</div>
                  </div>
                </div>
                <div class="mt-2 small-muted">Klik untuk edit isi:</div>
                <div contenteditable="true" class="contenteditable" data-field="content" data-id="{{ $r->id }}">{{ $r->content }}</div>

                <form class="form-inline mt-2" action="{{ route('executive.homepages.update', ['section'=>'review','id'=>$r->id]) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <input class="input" type="number" name="rating" value="{{ $r->rating }}" min="1" max="5" placeholder="Rating">
                  <input class="input" type="text" name="date" value="{{ $r->date }}" placeholder="Tanggal">
                  <label class="small-muted" style="display:flex;align-items:center;gap:6px">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" {{ $r->active ? 'checked' : '' }}> Aktif
                  </label>
                  <button class="btn btn-sm btn-warning" type="submit">Simpan</button>
                </form>

                <div class="hr"></div>
                <form action="{{ route('executive.homepages.destroy', ['section'=>'review','id'=>$r->id]) }}" method="POST" onsubmit="return confirm('Hapus review ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </details>