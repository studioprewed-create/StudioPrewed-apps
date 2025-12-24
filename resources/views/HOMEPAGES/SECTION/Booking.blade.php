@php
    $addonGroups = isset($addons) ? $addons->groupBy('kategori') : collect();
@endphp

<div class="booking-container" id="bookingWizard">

@if (session('success'))
    <div class="alert alert-success" style="margin:16px auto;max-width:980px">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" style="margin:16px auto;max-width:980px">
        <strong>Gagal menyimpan:</strong>
        <ul style="margin:8px 0 0 18px">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($dataDiri)
<div id="prefillData"
    data-nama="{{ e($dataDiri->nama) }}"
    data-phone="{{ e($dataDiri->phone) }}"
    data-gender="{{ e($dataDiri->jenis_kelamin) }}"
    data-nama-pasangan="{{ e($dataDiri->nama_pasangan) }}"
    data-phone-pasangan="{{ e($dataDiri->phone_pasangan) }}"
    data-gender-pasangan="{{ e($dataDiri->jenis_kelamin_pasangan) }}"
    data-email="{{ e($user->email ?? Auth::user()->email ?? '') }}">
</div>
@endif

    <h2>Form Booking Prewed</h2>

    <div class="steps-indicator">
        <div class="progress" id="bwProgress"></div>
        <div class="step-circle active"></div>
        <div class="step-circle"></div>
        <div class="step-circle"></div>
        <div class="step-circle"></div>
    </div>

    {{-- STEP 1 --}}
    <div class="step active" data-step="1">
        <div class="grid-2">
            <div>
                <div class="step-head">
                    <h4>CPP (Pria)</h4>
                </div>
                <label>Nama CPP (Wajib isi )</label><input type="text" id="nama_cpp">
                <label>Email CPP (Opsional)</label><input type="email" id="email_cpp">
                <label>No. Telp CPP (Wajib isi)</label><input type="text" id="phone_cpp">
                <label>Alamat CPP (opsional)</label><input type="text" id="alamat_cpp">
            </div>
            <div>
                <div class="step-head">
                    <h4>CPW (Perempuan)</h4>
                </div>
                <label>Nama CPW (Wajib isi )</label><input type="text" id="nama_cpw">
                <label>Email CPW (Opsional)</label><input type="email" id="email_cpw">
                <label>No. Telp CPW (Wajib isi )</label><input type="text" id="phone_cpw">
                <label>Alamat CPW (opsional)</label><input type="text" id="alamat_cpw">
            </div>
        </div>
    </div>

    {{-- STEP 2 --}}
    <div class="step" data-step="2">
        <label>Pilih Paket</label>
        <select id="package_id">
            <option value="">-- pilih paket --</option>
            @foreach($packages as $pkg)
                <option value="{{ $pkg->id }}" data-durasi="{{ (int)$pkg->durasi }}">
                    {{ $pkg->nama_paket }} ({{ (int)$pkg->durasi }} menit)
                </option>
            @endforeach
        </select>

        <div class="grid-2">
            <div>
                <label>Tanggal Photoshoot</label>
                <input type="date" id="photoshoot_date" min="{{ now()->toDateString() }}">
            </div>
            <div>
                <label>Style</label>
                <select id="style">
                    <option value="">-- pilih style --</option>
                    <option value="Hijab">Hijab</option>
                    <option value="HairDo">HairDo</option>
                </select>
            </div>
        </div>

        <label>Pilih Slot Waktu</label>
        <div id="slotList" class="slots-grid">
            <p style="opacity:.7">Pilih paket & tanggal untuk melihat slot.</p>
        </div>

        <label>Tema Baju Utama (opsional)</label>
        <div class="grid-3">
            <div>
                <label>Nama Tema</label>
                <select id="tema_nama">
                    <option value="">-- pilih nama tema --</option>
                    @foreach($temas->groupBy('nama') as $nama => $list)
                        <option value="{{ $nama }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Kode Tema</label>
                <select id="tema_kode" disabled>
                    <option value="">-- pilih kode tema --</option>
                    @foreach($temas as $t)
                        <option
                            value="{{ $t->kode }}"
                            data-nama="{{ $t->nama }}"
                            data-id="{{ $t->id }}">
                            {{ $t->kode }} - {{ $t->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ADDON SECTION --}}
        <div class="addon-section" style="margin-top:24px">
            <h3>Addon (Opsional)</h3>
            <p style="opacity:.8;font-size:0.9rem">
                Pilih addon untuk menambah slot waktu, tema baju tambahan, atau fitur lain.
                Tambah slot waktu akan membuka pilihan slot ekstra, dan tema tambahan tidak boleh sama
                dengan tema utama.
            </p>

            @if($addonGroups->isNotEmpty())
                <div class="addons-grid">
                   @foreach ($addonGroups as $kategori => $group)
                    <div class="addon-group addon-kat-{{ $kategori }}">
                        <h4>{{ $group->first()->kategori_label }}</h4>

                        @foreach ($group as $addon)
                            <label class="addon-item">
                                <input
                                    type="checkbox"
                                    class="addon-check"
                                    data-id="{{ $addon->id }}"
                                    data-kategori="{{ $addon->kategori }}"
                                    data-harga="{{ $addon->harga }}"
                                    data-durasi="{{ $addon->durasi ?? 0 }}"
                                >
                                <span class="addon-name">{{ $addon->nama }}</span>
                                <small>{{ $addon->durasi_label }}</small>
                                <span>Rp {{ number_format($addon->harga, 0, ',', '.') }}</span>
                            </label>
                        @endforeach
                    </div>
                @endforeach
                </div>
            @else
                <p style="opacity:.7">Belum ada addon yang aktif.</p>
            @endif
        </div>

        {{-- Extra slot dari addon kategori 1 --}}
        <div id="extraSlotWrapper" style="display:none;margin-top:16px">
            <h4>Slot Tambahan (Addon Slot Waktu)</h4>
            <p style="opacity:.7;font-size:0.85rem">
                Slot ini menggunakan durasi dari addon kategori 1 (mis. 60 atau 120 menit) dan tetap
                mengikuti kapasitas studio. Wajib berbeda dari slot utama.
            </p>
            <div id="extraSlotList" class="slots-grid">
                <p style="opacity:.7">
                    Pilih addon "Tambah Slot Waktu" & slot utama terlebih dahulu.
                </p>
            </div>
        </div>

        {{-- Extra tema dari addon kategori 2 --}}
        <div id="extraTemaWrapper" style="display:none;margin-top:16px">
            <h4>Tema Baju Tambahan (Addon Tema)</h4>
            <div class="grid-3">
                <div>
                    <label>Nama Tema Tambahan</label>
                    <select id="tema2_nama">
                        <option value="">-- pilih nama tema --</option>
                        @foreach($temas->groupBy('nama') as $nama => $list)
                            <option value="{{ $nama }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Kode Tema Tambahan</label>
                    <select id="tema2_kode" disabled>
                        <option value="">-- pilih kode tema --</option>
                        @foreach($temas as $t)
                            <option
                                value="{{ $t->kode }}"
                                data-nama="{{ $t->nama }}"
                                data-id="{{ $t->id }}">
                                {{ $t->kode }} - {{ $t->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <label>Wedding Date (opsional)</label>
        <input type="date" id="wedding_date" min="{{ now()->toDateString() }}">

        <label>Notes (opsional)</label>
        <textarea id="notes" placeholder="Catatan tambahan"></textarea>
    </div>

    {{-- STEP 3 --}}
    <div class="step" data-step="3">
        <div class="grid-2">
            <div>
                <h4>CPP</h4>
                <label>Instagram</label><input type="text" id="ig_cpp" placeholder="@cpp">
                <label>TikTok</label><input type="text" id="tiktok_cpp" placeholder="@cpp">
            </div>
            <div>
                <h4>CPW</h4>
                <label>Instagram</label><input type="text" id="ig_cpw" placeholder="@cpw">
                <label>TikTok</label><input type="text" id="tiktok_cpw" placeholder="@cpw">
            </div>
        </div>
    </div>

    {{-- STEP 4 --}}
    <div class="step" data-step="4">
        <div class="summary-card" id="summaryBox"></div>
        <form id="finalForm" method="POST" action="{{ route('executive.bookingClient.store') }}">
            @csrf
            <div id="hiddenBag"></div>
            <button type="button" class="btn" id="submitBtn">Kirim Booking</button>
        </form>
    </div>

    <div class="navigation">
        <button class="btn" id="prevBtn" disabled>Kembali</button>
        <button class="btn" id="nextBtn">Lanjut</button>
    </div>
</div>
