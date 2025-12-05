    <!-- Section Utama untuk Profile dan Booking -->
    <section class="account-main-section" id="akun">
        <div class="account-container">
            <!-- Profile Section -->
            <div class="akun-container">
                <div class="section-header">
                    <h2>Profil</h2>
                    <p>Kelola informasi akun Anda di sini</p>
                </div>

                <div class="profile-picture-section">
                    <div class="profile-picture-container">
                        <img id="profileImage" src="{{ asset('asset/default-avatar.jpg') }}" alt="Foto Profil" class="profile-image">
                    </div>
                    <p class="profile-picture-hint">Foto profil Anda</p>
                </div>

                <!-- Informasi Profil (Read-only) dengan garis dan dot -->
                <div class="profile-info with-line">
                    <div class="info-group">
                        <label>Nama Lengkap</label>
                        <div class="info-value" id="displayFullname">John Doe</div>
                    </div>

                    <div class="info-group">
                        <label>Username</label>
                        <div class="info-value" id="displayUsername">johndoe</div>
                    </div>

                    <div class="info-group">
                        <label>Nomor Telepon</label>
                        <div class="info-value" id="displayPhone">+62 812-3456-7890</div>
                    </div>

                    <div class="info-group">
                        <label>Email</label>
                        <div class="info-value" id="displayEmail">johndoe@example.com</div>
                    </div>

                    <div class="info-group">
                        <label>Status Akun</label>
                        <div class="info-value status-verified">Terverifikasi</div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="profile-actions">
                    <button type="button" class="akun-edit-btn" id="editProfileBtn">
                        <i class="fas fa-edit"></i> Edit Profil
                    </button>
                    <button type="button" class="akun-secondary-btn" id="changePasswordBtn">
                        <i class="fas fa-key"></i> Ubah Password
                    </button>
                </div>
            </div>

            <!-- Riwayat Booking Section dengan Desain Tiket -->
            <div class="booking-container">
                <div class="booking-header">
                    <h2>Riwayat Booking</h2>
                    <p>Lihat riwayat pemesanan Anda</p>
                </div>

                <div class="booking-list">
                    <!-- Tiket 1 -->
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

                    <!-- Tiket 2 -->
                    <div class="ticket-card">
                        <div class="ticket-header">
                            <h3 class="ticket-title">Paket Engagement</h3>
                            <span class="ticket-status status-upcoming">Akan Datang</span>
                        </div>
                        <div class="ticket-body">
                            <div class="ticket-detail">
                                <span class="detail-label">Tanggal</span>
                                <span class="detail-value">20 Januari 2024</span>
                            </div>
                            <div class="ticket-detail">
                                <span class="detail-label">Waktu</span>
                                <span class="detail-value">10:00 - 15:00</span>
                            </div>
                            <div class="ticket-detail">
                                <span class="detail-label">Lokasi</span>
                                <span class="detail-value">Outdoor Session</span>
                            </div>
                            <div class="ticket-detail">
                                <span class="detail-label">Total</span>
                                <span class="detail-value">Rp 3.500.000</span>
                            </div>
                        </div>
                        <div class="ticket-side">
                            <div class="ticket-code">#SPE002</div>
                            <div class="ticket-qr">QR CODE</div>
                        </div>
                        <div class="ticket-footer">
                            <span class="ticket-note">Konfirmasi kehadiran 3 hari sebelum sesi</span>
                            <button class="ticket-action">Lihat Detail</button>
                        </div>
                    </div>

                    <!-- Tiket 3 -->
                    <div class="ticket-card">
                        <div class="ticket-header">
                            <h3 class="ticket-title">Paket Prewedding</h3>
                            <span class="ticket-status status-completed">Selesai</span>
                        </div>
                        <div class="ticket-body">
                            <div class="ticket-detail">
                                <span class="detail-label">Tanggal</span>
                                <span class="detail-value">10 November 2023</span>
                            </div>
                            <div class="ticket-detail">
                                <span class="detail-label">Waktu</span>
                                <span class="detail-value">08:00 - 16:00</span>
                            </div>
                            <div class="ticket-detail">
                                <span class="detail-label">Lokasi</span>
                                <span class="detail-value">Studio + Outdoor</span>
                            </div>
                            <div class="ticket-detail">
                                <span class="detail-label">Total</span>
                                <span class="detail-value">Rp 4.200.000</span>
                            </div>
                        </div>
                        <div class="ticket-side">
                            <div class="ticket-code">#SPP003</div>
                            <div class="ticket-qr">QR CODE</div>
                        </div>
                        <div class="ticket-footer">
                            <span class="ticket-note">Foto sudah tersedia untuk diunduh</span>
                            <button class="ticket-action">Lihat Detail</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>