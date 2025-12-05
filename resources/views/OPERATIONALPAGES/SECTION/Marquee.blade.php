<details class="acc">
    <summary>
    <i class="fa-solid fa-arrows-left-right"></i> Marquee (Pills)
    <i class="fa-solid fa-chevron-right chev"></i>
    </summary>
    <div class="acc-body" id="marquee">
    <form class="form-inline" action="{{ route('executive.homepages.store', ['section'=>'marquee']) }}" method="POST" style="margin-bottom:12px">
        @csrf
        <input class="input" type="text" name="text" placeholder="Teks pill" required style="flex:1;min-width:240px">
        <input class="input" type="text" name="icon_class" placeholder="Icon class (ops)">
        <input class="input" type="number" name="order" placeholder="Urutan (ops)">
        <label class="small-muted" style="display:flex;align-items:center;gap:6px">
        <input type="hidden" name="active" value="0">
        <input type="checkbox" name="active" value="1" checked> Aktif
        </label>
        <button class="btn btn-sm" type="submit">Tambah Marquee</button>
    </form>
    <div class="block-rel">
        <table class="tbl">
        <thead>
            <tr>
            <th style="width:90px">ID</th>
            <th>Teks</th>
            <th style="width:260px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($marquees as $m)
            <tr>
                <td>#{{ $m->id }}</td>
                <td>
                <form action="{{ route('executive.homepages.update', ['section'=>'marquee','id'=>$m->id]) }}" method="POST" class="form-inline" style="gap:8px">
                    @csrf
                    @method('PUT')
                    <input class="input" type="text" name="text" value="{{ old('text', $m->text) }}" required>
                    <div class="tbl-actions">
                    <button class="btn btn-sm btn-warning" type="submit">Simpan</button>
                    </div>
                </form>
                </td>
                <td>
                <div class="tbl-actions">
                    <form action="{{ route('executive.homepages.destroy', ['section'=>'marquee','id'=>$m->id]) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                    </form>
                </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="small-muted">Belum ada data marquee.</td></tr>
            @endforelse
        </tbody>
        </table>
    </div>
    </div>
</details>