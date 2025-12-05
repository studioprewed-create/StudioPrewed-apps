 <details class="acc">
      <summary>
        <i class="fa-solid fa-circle-question"></i> FAQ
        <i class="fa-solid fa-chevron-right chev"></i>
      </summary>
      <div class="acc-body" id="faq">
        <form method="GET" class="section-search">
          <input class="input" type="text" name="faq_q" placeholder="Cari pertanyaan / jawaban..." value="{{ request('faq_q') }}">
          <button class="btn btn-sm" type="submit">Cari</button>
        </form>

        <form class="form-inline" action="{{ route('executive.homepages.store', ['section'=>'faq']) }}" method="POST" style="margin-bottom:12px">
          @csrf
          <input class="input" type="text" name="question" placeholder="Pertanyaan" required style="flex:1;min-width:220px">
          <input class="input" type="text" name="answer" placeholder="Jawaban (ops)" style="flex:2;min-width:280px">
          <input class="input" type="number" name="order" placeholder="Urutan">
          <label class="small-muted" style="display:flex;align-items:center;gap:6px">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" checked> Aktif
          </label>
          <button class="btn btn-sm" type="submit">Tambah FAQ</button>
        </form>

        @foreach($faqs as $f)
          <div class="block-rel" data-faq-id="{{ $f->id }}" style="margin-bottom:10px">
            <div class="admin-badge">FAQ #{{ $f->id }}</div>
            <div class="small-muted">Klik untuk edit:</div>
            <div contenteditable="true" class="contenteditable" data-field="question" data-id="{{ $f->id }}">{{ $f->question }}</div>
            <div contenteditable="true" class="contenteditable" data-field="answer" data-id="{{ $f->id }}">{{ $f->answer }}</div>

            <form class="form-inline mt-2" action="{{ route('executive.homepages.update', ['section'=>'faq','id'=>$f->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input class="input" type="number" name="order" value="{{ $f->order }}" placeholder="Urutan">
              <label class="small-muted" style="display:flex;align-items:center;gap:6px">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" {{ $f->active ? 'checked' : '' }}> Aktif
              </label>
              <button class="btn btn-sm btn-warning" type="submit">Simpan</button>
            </form>

            <div class="hr"></div>
            <form action="{{ route('executive.homepages.destroy', ['section'=>'faq','id'=>$f->id]) }}" method="POST" onsubmit="return confirm('Hapus FAQ ini?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </div>
        @endforeach
      </div>
    </details>
</div>