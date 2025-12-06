  @if ($errors->any())
    <div class="alert alert-danger">
      <ul style="margin:0;padding-left:18px">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

<div class="v-accordion" id="Videoplay">
<details class="acc" {{ request()->hash === 'slides' ? 'open' : '' }}>
      <summary>
        <i class="fa-solid fa-images"></i> Videoplay
        <i class="fa-solid fa-chevron-right chev"></i>
      </summary>
      <div class="acc-body" id="slides">
        <form method="GET" class="section-search">
          <input class="input" type="text" name="slides_q" placeholder="Cari judul / subtitle..." value="{{ request('slides_q') }}">
          <button class="btn btn-sm" type="submit">Cari</button>
        </form>

        <form class="form-inline" action="{{ route('executive.homepages.store', ['section'=>'slide']) }}" method="POST" enctype="multipart/form-data" style="margin-bottom:12px">
          @csrf
          <input class="input" type="text" name="title" placeholder="Title (opsional)">
          <input class="input" type="text" name="subtitle" placeholder="Subtitle (opsional)">
          <input class="input" type="number" name="order" placeholder="Urutan">
          <label class="small-muted" style="display:flex;align-items:center;gap:6px">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" checked> Aktif
          </label>
          <input class="input" type="file" name="image" accept="image/*,video/*" required>
          <button class="btn btn-sm" type="submit">Tambah Slide</button>
        </form>

        <div class="row">
          @foreach($slides as $slide)
            @php $path = $slide->image ? asset('public/storage/'.$slide->image) : null; @endphp
            <div class="col-md-3">
              <div class="block-rel" data-slide-id="{{ $slide->id }}">
                <div class="admin-badge">Slide #{{ $slide->id }}</div>
                <div class="img-picker" data-type="slide" data-id="{{ $slide->id }}"><i class="fas fa-image"></i> Ganti</div>

                @if($path)
                  @if(\Illuminate\Support\Str::endsWith($slide->image, ['.mp4','.mov','.avi','.webm']))
                    <video controls style="width:100%;height:150px;object-fit:cover;border-radius:8px">
                      <source src="{{ $path }}" type="video/mp4"> Browser tidak mendukung video.
                    </video>
                  @else
                    <div class="ci" style="background-image:url('{{ $path }}')"></div>
                  @endif
                @else
                  <div class="ci" style="background-image:url('{{ asset('asset/IMGhome/bg1.jpg') }}')"></div>
                @endif

                <div class="mt-2 small-muted">Klik untuk edit teks:</div>
                <div contenteditable="true" class="contenteditable" data-field="title" data-id="{{ $slide->id }}">{{ $slide->title }}</div>
                <div contenteditable="true" class="contenteditable" data-field="subtitle" data-id="{{ $slide->id }}">{{ $slide->subtitle }}</div>

                <form class="form-inline mt-2" action="{{ route('executive.homepages.update', ['section'=>'slide','id'=>$slide->id]) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <input class="input" type="number" name="order" value="{{ $slide->order }}" placeholder="Urutan">
                  <label class="small-muted" style="display:flex;align-items:center;gap:6px">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" {{ $slide->active ? 'checked' : '' }}> Aktif
                  </label>
                  <button class="btn btn-sm btn-warning" type="submit">Simpan</button>
                </form>

                <div class="hr"></div>
                <form action="{{ route('executive.homepages.destroy', ['section'=>'slide','id'=>$slide->id]) }}" method="POST" onsubmit="return confirm('Hapus slide ini?')">
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