
<div class="page-header">
    <div>
        <h1>Jadwal Pesanan</h1>
        <div class="subtitle">Kelola booking prewedding studio</div>
    </div>
    <div class="header-actions">
        <button class="btn btn-primary" id="btnOpenCreateBooking">
            <i class="fa-solid fa-plus"></i> Tambah Booking
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ================= LIST BOOKING ================= --}}
<div class="grid-cards">
@foreach($bookings as $b)
    <div class="card-elev">
        <div class="card-body">
            <div class="card-head">
                <div>
                    <div class="title">{{ $b->nama_cpp }} & {{ $b->nama_cpw }}</div>
                    <div class="small muted">{{ $b->kode_pesanan }}</div>
                </div>
                <span class="role-badge badge-{{ $b->status }}">
                    {{ strtoupper($b->status) }}
                </span>
            </div>

            <div class="meta muted">
                {{ \Carbon\Carbon::parse($b->photoshoot_date)->format('d M Y') }}
                • {{ $b->start_time }} - {{ $b->end_time }}
            </div>

            <div class="card-actions">
                <button class="btn btn-outline btnDetail" data-id="{{ $b->id }}">
                    <i class="fa-solid fa-eye"></i>
                </button>
                <button class="btn btn-outline btnEdit" data-id="{{ $b->id }}">
                    <i class="fa-solid fa-pen"></i>
                </button>

                <form method="POST"
                      action="{{ route('executive.homepages.destroy',['section'=>'bookingexecutive','id'=>$b->id]) }}"
                      onsubmit="return confirm('Hapus booking ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>

                <a class="btn btn-success"
                   target="_blank"
                   href="https://wa.me/{{ $b->phone_cpp }}">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>
@endforeach
</div>

{{-- =========================================================
 MODAL TAMBAH BOOKING (FULL BOOKING CLIENT)
========================================================= --}}
<div class="custom-modal-backdrop" id="bdCreate"></div>
<div class="custom-modal" id="modalCreate">
  <div class="modal-content modal-xl">
    <div class="modal-header">
      <h5>Tambah Booking</h5>
      <button class="btn btn-secondary" id="closeCreate">✕</button>
    </div>
    <div class="modal-body">

@php
    $addonGroups = $addons->groupBy('kategori');
@endphp

<div class="booking-container" id="bookingWizard">

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
            <label>Nama CPP</label><input id="nama_cpp">
            <label>Email CPP</label><input id="email_cpp">
            <label>No Telp CPP</label><input id="phone_cpp">
            <label>Alamat CPP</label><input id="alamat_cpp">
        </div>
        <div>
            <label>Nama CPW</label><input id="nama_cpw">
            <label>Email CPW</label><input id="email_cpw">
            <label>No Telp CPW</label><input id="phone_cpw">
            <label>Alamat CPW</label><input id="alamat_cpw">
        </div>
    </div>
</div>

{{-- STEP 2 --}}
<div class="step" data-step="2">

<label>Pilih Paket</label>
<select id="package_id">
    <option value="">-- pilih paket --</option>
    @foreach($packages as $pkg)
        <option value="{{ $pkg->id }}" data-durasi="{{ $pkg->durasi }}">
            {{ $pkg->nama_paket }} ({{ $pkg->durasi }} menit)
        </option>
    @endforeach
</select>

<div class="grid-2">
    <div>
        <label>Tanggal</label>
        <input type="date" id="photoshoot_date">
    </div>
    <div>
        <label>Style</label>
        <select id="style">
            <option value="">-- pilih --</option>
            <option value="Hijab">Hijab</option>
            <option value="HairDo">HairDo</option>
        </select>
    </div>
</div>

<label>Pilih Slot</label>
<div id="slotList" class="slots-grid"></div>

<label>Tema Utama</label>
<div class="grid-3">
    <select id="tema_nama">
        <option value="">-- nama --</option>
        @foreach($temas->groupBy('nama') as $n => $l)
            <option value="{{ $n }}">{{ $n }}</option>
        @endforeach
    </select>
    <select id="tema_kode" disabled>
        <option value="">-- kode --</option>
        @foreach($temas as $t)
            <option value="{{ $t->kode }}" data-nama="{{ $t->nama }}" data-id="{{ $t->id }}">
                {{ $t->kode }}
            </option>
        @endforeach
    </select>
</div>

<div class="addon-section">
@foreach($addonGroups as $kat => $group)
    <div class="addon-group">
        <h4>{{ $group->first()->kategori_label }}</h4>
        @foreach($group as $a)
            <label class="addon-item">
                <input type="checkbox"
                       class="addon-check"
                       data-id="{{ $a->id }}"
                       data-kategori="{{ $a->kategori }}"
                       data-harga="{{ $a->harga }}"
                       data-durasi="{{ $a->durasi }}">
                {{ $a->nama }}
            </label>
        @endforeach
    </div>
@endforeach
</div>

<div id="extraSlotWrapper" style="display:none">
    <h4>Slot Tambahan</h4>
    <div id="extraSlotList" class="slots-grid"></div>
</div>

<div id="extraTemaWrapper" style="display:none">
    <label>Tema Tambahan</label>
    <select id="tema2_nama">
        <option value="">-- nama --</option>
        @foreach($temas->groupBy('nama') as $n => $l)
            <option value="{{ $n }}">{{ $n }}</option>
        @endforeach
    </select>
    <select id="tema2_kode" disabled>
        <option value="">-- kode --</option>
        @foreach($temas as $t)
            <option value="{{ $t->kode }}" data-nama="{{ $t->nama }}" data-id="{{ $t->id }}">
                {{ $t->kode }}
            </option>
        @endforeach
    </select>
</div>

<label>Notes</label>
<textarea id="notes"></textarea>
</div>

{{-- STEP 3 --}}
<div class="step" data-step="3">
    <div class="grid-2">
        <div>
            <label>IG CPP</label><input id="ig_cpp">
            <label>TikTok CPP</label><input id="tiktok_cpp">
        </div>
        <div>
            <label>IG CPW</label><input id="ig_cpw">
            <label>TikTok CPW</label><input id="tiktok_cpw">
        </div>
    </div>
</div>

{{-- STEP 4 --}}
<div class="step" data-step="4">
    <div id="summaryBox"></div>

    <form id="finalForm"
          method="POST"
          action="{{ route('executive.homepages.store',['section'=>'bookingexecutive']) }}">
        @csrf
        <div id="hiddenBag"></div>
        <button type="button" class="btn" id="submitBtn">Simpan Booking</button>
    </form>
</div>

<div class="navigation">
    <button class="btn" id="prevBtn" disabled>Kembali</button>
    <button class="btn" id="nextBtn">Lanjut</button>
</div>

</div>

    </div>
  </div>
</div>

{{-- =========================================================
 MODAL EDIT & DETAIL (STRUKTUR SAMA, LOAD VIA JS)
========================================================= --}}
<div class="custom-modal-backdrop" id="bdEdit"></div>
<div class="custom-modal" id="modalEdit">
  <div class="modal-content modal-xl">
    <div class="modal-header">
      <h5>Edit Booking</h5>
      <button class="btn btn-secondary" id="closeEdit">✕</button>
    </div>
    <div class="modal-body" id="editContainer"></div>
  </div>
</div>

<div class="custom-modal-backdrop" id="bdDetail"></div>
<div class="custom-modal" id="modalDetail">
  <div class="modal-content modal-xl">
    <div class="modal-header">
      <h5>Detail Booking</h5>
      <button class="btn btn-secondary" id="closeDetail">✕</button>
    </div>
    <div class="modal-body" id="detailContainer"></div>
  </div>
</div>
