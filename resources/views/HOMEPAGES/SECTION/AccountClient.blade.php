@if(Auth::user()->role !== 'CLIENT')
  <section class="account-main-section" id="akun">
    <div class="account-container">
        <!-- Profile Section -->
        <div class="akun-container">
            <div class="section-header">
                <h2>Anda Bukan Client -_- mohon akses data dashboard anda ---- Terima Kasih ----</h2>
            </div>
        </div>
    </div>
  </section>
@else
<section class="account-main-section" id="akun">
    <div class="account-container">
        <!-- Profile Section -->
        <div class="akun-container">
            <div class="section-header">
                <h2>Data Akun</h2>
            </div>

            {{-- Pesan flash & error --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Informasi Profil (Read-only) -->
            <div class="profile-info with-line">
                <div class="info-group">
                    <label>Nama Lengkap</label>
                    <div class="info-value" id="displayFullname">
                        {{ optional($dataDiri)->nama ?? $user->name ?? '-' }}
                    </div>
                </div>

                <div class="info-group">
                    <label>Username</label>
                    <div class="info-value" id="displayUsername">
                        {{ $user->username ?? $user->name ?? '-' }}
                    </div>
                </div>

                <div class="info-group">
                    <label>Nomor Telepon</label>
                    <div class="info-value" id="displayPhone">
                        {{ optional($dataDiri)->phone ?? '-' }}
                    </div>
                </div>

                <div class="info-group">
                    <label>Jenis Kelamin Pasangan</label>
                    <div class="info-value">
                        {{ optional($dataDiri)->jenis_kelamin ?? '-' }}
                    </div>
                </div>

                <div class="info-group">
                    <label>Email</label>
                    <div class="info-value" id="displayEmail">
                        {{ $user->email ?? '-' }}
                    </div>
                </div>

                <div class="section-header">
                    <h2>Data Pasangan</h2>
                </div>

                <div class="info-group">
                    <label>Nama Pasangan</label>
                    <div class="info-value">
                        {{ optional($dataDiri)->nama_pasangan ?? '-' }}
                    </div>
                </div>

                <div class="info-group">
                    <label>Nomor Telepon Pasangan</label>
                    <div class="info-value">
                        {{ optional($dataDiri)->phone_pasangan ?? '-' }}
                    </div>
                </div>

                <div class="info-group">
                    <label>Jenis Kelamin Pasangan</label>
                    <div class="info-value">
                        {{ optional($dataDiri)->jenis_kelamin_pasangan ?? '-' }}
                    </div>
                </div>

                <div class="info-group">
                    <label>Tanggal Lahir Pasangan</label>
                    <div class="info-value">
                        {{ optional($dataDiri)->tanggal_lahir_pasangan ?? '-' }}
                    </div>
                </div>
            </div>
            <div class="profile-actions">
                <button type="button" class="akun-edit-btn"
                        onclick="document.getElementById('accountDetails').toggleAttribute('open')">
                    <i class="fas fa-edit"></i> Tambah / Edit Data Diri
                </button>
                <button type="button" class="akun-secondary-btn" id="changePasswordBtn">
                    <i class="fas fa-key"></i> Ubah Password
                </button>
            </div>
            <details class="acc" id="accountDetails" @if($errors->any()) open @endif style="margin-top:16px;">
                <summary>
                    <i class="fa-solid fa-user"></i> Data Diri & Pasangan
                    <i class="fa-solid fa-chevron-right chev"></i>
                </summary>

                <div class="acc-body">
                    <form class="form-inline"
                          method="POST"
                          action="{{ $dataDiri
                                    ? route('Account.update', $dataDiri->id)
                                    : route('Account.store') }}">
                        @csrf
                        @if($dataDiri)
                            @method('PUT')
                        @endif

                        {{-- Data Diri --}}
                        <div class="account-form-group">
                            <h4 class="account-form-title">Data Diri</h4>

                            <label for="nama" class="input-label">Nama lengkap</label>
                            <input class="input" id="nama" type="text" name="nama"
                                   placeholder="Nama lengkap"
                                   value="{{ old('nama', optional($dataDiri)->nama ?? $user->name ?? '') }}" required>

                            <label for="phone" class="input-label">No HP / WA</label>
                            <input class="input" id="phone" type="text" name="phone"
                                   placeholder="No HP / WA"
                                   value="{{ old('phone', optional($dataDiri)->phone) }}">

                            <label for="jenis_kelamin" class="input-label">Jenis kelamin</label>
                            <select class="input" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="">Pilih jenis kelamin</option>
                                <option value="laki-laki"
                                    {{ old('jenis_kelamin', optional($dataDiri)->jenis_kelamin) === 'laki-laki' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>
                                <option value="perempuan"
                                    {{ old('jenis_kelamin', optional($dataDiri)->jenis_kelamin) === 'perempuan' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>

                            <label class="input-label" for="tanggal_lahir">Tanggal lahir</label>
                            <input class="input" id="tanggal_lahir" type="date" name="tanggal_lahir"
                                   value="{{ old('tanggal_lahir', optional($dataDiri)->tanggal_lahir) }}">

                            <label class="input-label" for="tanggal_pernikahan">Tanggal pernikahan</label>
                            <input class="input" id="tanggal_pernikahan" type="date" name="tanggal_pernikahan"
                                   value="{{ old('tanggal_pernikahan', optional($dataDiri)->tanggal_pernikahan) }}">
                        </div>

                        {{-- Data Pasangan --}}
                        <div class="account-form-group" style="margin-top:16px;">
                            <h4 class="account-form-title">Data Pasangan</h4>

                            <label for="nama_pasangan" class="input-label">Nama pasangan</label>
                            <input class="input" id="nama_pasangan" type="text" name="nama_pasangan"
                                   placeholder="Nama pasangan"
                                   value="{{ old('nama_pasangan', optional($dataDiri)->nama_pasangan) }}">

                            <label for="phone_pasangan" class="input-label">No HP / WA pasangan</label>
                            <input class="input" id="phone_pasangan" type="text" name="phone_pasangan"
                                   placeholder="No HP / WA pasangan"
                                   value="{{ old('phone_pasangan', optional($dataDiri)->phone_pasangan) }}">

                            <label for="jenis_kelamin_pasangan" class="input-label">Jenis kelamin pasangan</label>
                            <select class="input" id="jenis_kelamin_pasangan" name="jenis_kelamin_pasangan">
                                <option value="">Pilih jenis kelamin pasangan</option>
                                <option value="laki-laki"
                                    {{ old('jenis_kelamin_pasangan', optional($dataDiri)->jenis_kelamin_pasangan) === 'laki-laki' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>
                                <option value="perempuan"
                                    {{ old('jenis_kelamin_pasangan', optional($dataDiri)->jenis_kelamin_pasangan) === 'perempuan' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>

                            <label class="input-label" for="tanggal_lahir_pasangan">Tanggal lahir pasangan</label>
                            <input class="input" id="tanggal_lahir_pasangan" type="date" name="tanggal_lahir_pasangan"
                                   value="{{ old('tanggal_lahir_pasangan', optional($dataDiri)->tanggal_lahir_pasangan) }}">
                        </div>

                        <div style="margin-top:16px; display:flex; gap:8px; flex-wrap:wrap;">
                            <button class="btn btn-sm" type="submit">
                                {{ $dataDiri ? 'Simpan Perubahan' : 'Tambah Data' }}
                            </button>
                        </div>
                    </form>

                    @if($dataDiri)
                        <form method="POST"
                              action="{{ route('Account.destroy', $dataDiri->id) }}"
                              onsubmit="return confirm('Hapus data akun ini?')"
                              style="margin-top:8px; display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Hapus Data</button>
                        </form>
                    @endif
                </div>
            </details>
        </div>

        <div class="booking-container">
            <div class="booking-header">
                <h2>Riwayat Booking</h2>
                <p>Lihat riwayat pemesanan Anda</p>
            </div>

            <div class="booking-list">
                {{-- Nanti diganti data dinamis booking, sekarang dummy --}}
                <div class="ticket-card">
                    <div class="ticket-header">
                        <h3 class="ticket-title">Paket Premium Wedding</h3>
                        <span class="ticket-status status-completed">Selesai</span>
                    </div>
                    <div class="ticket-body">
                        <div class="ticket-detail">
                            <span class="detail-label">Tanggal</span>
                            <span class="detail-value">15 Desember 2023</span>
                        </div>
                        <div class="ticket-detail">
                            <span class="detail-label">Waktu</span>
                            <span class="detail-value">09:00 - 17:00</span>
                        </div>
                        <div class="ticket-detail">
                            <span class="detail-label">Lokasi</span>
                            <span class="detail-value">Studio Utama</span>
                        </div>
                        <div class="ticket-detail">
                            <span class="detail-label">Total</span>
                            <span class="detail-value">Rp 5.000.000</span>
                        </div>
                    </div>
                    <div class="ticket-side">
                        <div class="ticket-code">#SPW001</div>
                        <div class="ticket-qr">QR CODE</div>
                    </div>
                    <div class="ticket-footer">
                        <span class="ticket-note">Sesi foto telah selesai dengan hasil yang memuaskan</span>
                        <button class="ticket-action">Lihat Detail</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif