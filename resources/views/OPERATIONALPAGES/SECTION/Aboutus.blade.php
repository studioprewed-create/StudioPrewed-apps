     <details class="acc" {{ request()->hash === 'aboutus' ? 'open' : '' }}>
      <summary>
        <i class="fa-solid fa-user-tie"></i> About Us (Model)
        <i class="fa-solid fa-chevron-right chev"></i>
      </summary>

      <div class="acc-body" id="aboutus">
        <form class="form-inline mb-3"
              action="{{ route('executive.homepages.store', ['section'=>'aboutus']) }}"
              method="POST"
              enctype="multipart/form-data">
          @csrf
          <input class="input" type="text" name="title" placeholder="Judul" required>
          <input class="input" type="text" name="subtitle" placeholder="Sub Judul">
          <input class="input" type="text" name="description" placeholder="Deskripsi singkat">
            <select class="input" name="model_type" required>
              <option value="" disabled selected>Pilih Model</option>
              <option value="model1">Model 1</option>
              <option value="model2">Model 2</option>
              <option value="model3">Model 3</option>
            </select>
          <input class="input" type="number" name="order" placeholder="Urutan">
            <label class="small-muted d-flex align-items-center gap-1">
              <input type="hidden" name="active" value="0">
              <input type="checkbox" name="active" value="1" checked> Aktif
            </label>
          <input class="input" type="file" name="images[]" accept="image/*" multiple required>
          <button class="btn btn-sm btn-primary" type="submit">Tambah Data</button>
        </form>
        <div class="row g-3">
          @foreach($aboutus as $data)
            @php
              $imagePaths = is_array($data->image) ? $data->image : ($data->image ? [$data->image] : []);
              if(empty($imagePaths)) $imagePaths = ['asset/IMGhome/default.jpg'];
            @endphp
            <div class="col-md-4">
              <div class="block-rel p-2 border rounded">
                <div class="admin-badge mb-1">{{ ucfirst($data->model_type) }} #{{ $data->id }}</div>
                <div class="d-flex flex-wrap gap-2 mb-2">
                  @foreach($imagePaths as $img)
                    @php $path = Str::startsWith($img, 'http') ? $img : asset('public/storage/'.$img); @endphp
                    <div class="ci" style="width:80px;height:80px;background-image:url('{{ $path }}'); background-size:cover; background-position:center;"></div>
                  @endforeach
                </div>
                <div class="img-picker mb-2" data-type="aboutus" data-id="{{ $data->id }}">
                  <i class="fas fa-image"></i> Ganti
                </div>
                <div class="mb-2 small-muted">Klik untuk edit teks:</div>
                <div contenteditable="true" class="contenteditable mb-1" data-field="title" data-id="{{ $data->id }}">{{ $data->title }}</div>
                <div contenteditable="true" class="contenteditable mb-1" data-field="subtitle" data-id="{{ $data->id }}">{{ $data->subtitle }}</div>
                <div contenteditable="true" class="contenteditable mb-2" data-field="description" data-id="{{ $data->id }}">{{ $data->description }}</div>
                <form class="form-inline d-flex align-items-center gap-2 mb-2"
                      action="{{ route('executive.homepages.update', ['section'=>'aboutus','id'=>$data->id]) }}"
                      method="POST">
                  @csrf
                  @method('PUT')
                  <input class="input form-control form-control-sm" type="number" name="order" value="{{ $data->order }}" placeholder="Urutan" style="width:70px;">
                  <label class="small-muted mb-0 d-flex align-items-center gap-1">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" {{ $data->active ? 'checked' : '' }}> Aktif
                  </label>
                  <button class="btn btn-sm btn-warning" type="submit">Simpan</button>
                </form>

                <hr class="my-1">
                <form action="{{ route('executive.homepages.destroy', ['section'=>'aboutus','id'=>$data->id]) }}"
                      method="POST"
                      onsubmit="return confirm('Hapus item ini?')">
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