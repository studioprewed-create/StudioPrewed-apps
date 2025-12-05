<details class="acc" {{ request()->hash === 'services' ? 'open' : '' }}>
  <summary>
    <i class="fa-solid fa-camera-retro"></i> Portrait Services
    <i class="fa-solid fa-chevron-right chev"></i>
  </summary>

  <div class="acc-body" id="services">
    {{-- FORM TAMBAH SERVICE --}}
    <form class="form-inline mb-3"
          action="{{ route('executive.homepages.store', ['section' => 'service']) }}"
          method="POST"
          enctype="multipart/form-data">
      @csrf
      <input class="input" type="text" name="title" placeholder="Judul layanan" required>
      <input class="input" type="text" name="description" placeholder="Deskripsi singkat">

      <select class="input" name="category" required>
        <option value="" disabled selected>Pilih Kategori Portofolio</option>
        <option value="prewed">Prewed Session</option>
        <option value="family">Family Session</option>
        <option value="maternity">Maternity Shoot</option>
        <option value="postwedding">Post Wedding</option>
        <option value="beauty">Beauty Shoot</option>
        <option value="birthday">Birthday Session</option>
      </select>

      <input class="input" type="number" name="order" placeholder="Urutan">
      <label class="small-muted d-flex align-items-center gap-1">
        <input type="hidden" name="active" value="0">
        <input type="checkbox" name="active" value="1" checked> Aktif
      </label>

      <input class="input" type="file" name="image" accept="image/*" required>

      <button class="btn btn-sm btn-primary" type="submit">Tambah Service</button>
    </form>

    {{-- LIST DATA --}}
    <div class="row g-3">
      @foreach($services ?? [] as $svc)
        @php
          $img = $svc->image ? asset('storage/'.$svc->image) : asset('asset/IMGhome/default.jpg');
        @endphp
        <div class="col-md-4">
          <div class="block-rel p-2 border rounded">

            <div class="admin-badge mb-1">
              Service #{{ $svc->id }} – {{ $svc->category ?: '-' }}
            </div>

            <div class="d-flex flex-wrap gap-2 mb-2">
              <div class="ci"
                   style="width:100%;height:120px;background-image:url('{{ $img }}'); background-size:cover; background-position:center;">
              </div>
            </div>

            {{-- Trigger ganti gambar (inline) --}}
            <div class="img-picker mb-2" data-type="service" data-id="{{ $svc->id }}">
              <i class="fas fa-image"></i> Ganti Gambar
            </div>

            <div class="mb-2 small-muted">Klik untuk edit teks (inline):</div>

            <div contenteditable="true"
                 class="contenteditable mb-1"
                 data-field="title"
                 data-id="{{ $svc->id }}"
                 data-type="service">
              {{ $svc->title }}
            </div>

            <div contenteditable="true"
                 class="contenteditable mb-2"
                 data-field="description"
                 data-id="{{ $svc->id }}"
                 data-type="service">
              {{ $svc->description }}
            </div>

            <div contenteditable="true"
                 class="contenteditable mb-2"
                 data-field="category"
                 data-id="{{ $svc->id }}"
                 data-type="service">
              {{ $svc->category }}
            </div>

            <form class="form-inline d-flex align-items-center gap-2 mb-2"
                  action="{{ route('executive.homepages.update', ['section'=>'service','id'=>$svc->id]) }}"
                  method="POST"
                  enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input class="input form-control form-control-sm"
                     type="number"
                     name="order"
                     value="{{ $svc->order }}"
                     placeholder="Urutan"
                     style="width:70px;">
              <label class="small-muted mb-0 d-flex align-items-center gap-1">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" {{ $svc->active ? 'checked' : '' }}> Aktif
              </label>
              <button class="btn btn-sm btn-warning" type="submit">Simpan</button>
            </form>

            <hr class="my-1">

            <form action="{{ route('executive.homepages.destroy', ['section'=>'service','id'=>$svc->id]) }}"
                  method="POST"
                  onsubmit="return confirm('Hapus service ini?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger w-100">Hapus</button>
            </form>

          </div>
        </div>
      @endforeach
    </div>
  </div>
</details>
