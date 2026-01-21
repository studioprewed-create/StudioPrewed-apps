<div class="welcome-section">
            <h1>Jadwal Kerja Karyawan</h1>
            <p>Kelola jadwal kerja tim Anda untuk minggu ini</p>
            <a href="#" class="see-all">Ekspor Jadwal <i class="fas fa-download"></i></a>
        </div>


        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">Filter Jadwal</div>
            <div class="filter-controls">
                <div class="date-filter">
                    <label for="start-date">Dari Tanggal:</label>
                    <input type="text" id="start-date" class="date-input" placeholder="Pilih tanggal">
                </div>
                <div class="date-filter">
                    <label for="end-date">Sampai Tanggal:</label>
                    <input type="text" id="end-date" class="date-input" placeholder="Pilih tanggal">
                </div>
                <button class="filter-btn">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
            </div>
        </div>


        <!-- Schedule Table -->
        <div class="schedule-container">
            <div class="schedule-header">
                <h3>Jadwal Mingguan</h3>
                <div class="week-navigation">
                    <button class="week-btn" id="prev-week">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="current-week" id="current-week">3 - 9 September 2025</div>
                    <button class="week-btn" id="next-week">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Senin<br><span class="day-display">01 Sept</span></th>
                            <th>Selasa<br><span class="day-display">02 Sept</span></th>
                            <th>Rabu<br><span class="day-display">03 Sept</span></th>
                            <th>Kamis<br><span class="day-display">04 Sept</span></th>
                            <th>Jumat<br><span class="day-display">05 Sept</span></th>
                            <th>Sabtu<br><span class="day-display">06 Sept</span></th>
                            <th>Minggu<br><span class="day-display">07 Sept</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- Senin -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-16:00</span>
                                    <div class="staff-name">Budi Santoso</div>
                                    <span class="staff-role role-photographer">Photographer</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">09:00-17:00</span>
                                    <div class="staff-name">Sari Dewi</div>
                                    <span class="staff-role role-makeup">Makeup Artist</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">10:00-15:00</span>
                                    <div class="staff-name">Ahmad Fauzi</div>
                                    <span class="staff-role role-videographer">Videographer</span>
                                </div>
                            </td>
                            
                            <!-- Selasa -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-16:00</span>
                                    <div class="staff-name">Ahmad Fauzi</div>
                                    <span class="staff-role role-videographer">Videographer</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">09:00-17:00</span>
                                    <div class="staff-name">Dewi Lestari</div>
                                    <span class="staff-role role-editor">Editor</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">13:00-18:00</span>
                                    <div class="staff-name">Budi Santoso</div>
                                    <span class="staff-role role-photographer">Photographer</span>
                                </div>
                            </td>
                            
                            <!-- Rabu -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-12:00</span>
                                    <div class="staff-name">Rina Wijaya</div>
                                    <span class="staff-role role-admin">Admin</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">09:00-17:00</span>
                                    <div class="staff-name">Joko Prasetyo</div>
                                    <span class="staff-role role-director">Direktur</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">14:00-19:00</span>
                                    <div class="staff-name">Sari Dewi</div>
                                    <span class="staff-role role-makeup">Makeup Artist</span>
                                </div>
                            </td>
                            
                            <!-- Kamis -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">10:00-16:00</span>
                                    <div class="staff-name">Maya Sari</div>
                                    <span class="staff-role role-designer">Designer/Attire</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-17:00</span>
                                    <div class="staff-name">Fajar Nugroho</div>
                                    <span class="staff-role role-lead-editor">Lead Editor</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">12:00-20:00</span>
                                    <div class="staff-name">Ahmad Fauzi</div>
                                    <span class="staff-role role-videographer">Videographer</span>
                                </div>
                            </td>
                            
                            <!-- Jumat -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">09:00-16:00</span>
                                    <div class="staff-name">Citra Ayu</div>
                                    <span class="staff-role role-makeup">Makeup Artist</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">10:00-18:00</span>
                                    <div class="staff-name">Hendra Kurniawan</div>
                                    <span class="staff-role role-photographer">Photographer</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-15:00</span>
                                    <div class="staff-name">Dewi Lestari</div>
                                    <span class="staff-role role-editor">Editor</span>
                                </div>
                            </td>
                            
                            <!-- Sabtu -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-14:00</span>
                                    <div class="staff-name">Dian Pratama</div>
                                    <span class="staff-role role-videographer">Videographer</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">10:00-17:00</span>
                                    <div class="staff-name">Rizky Maulana</div>
                                    <span class="staff-role role-editor">Editor</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">13:00-19:00</span>
                                    <div class="staff-name">Hendra Kurniawan</div>
                                    <span class="staff-role role-photographer">Photographer</span>
                                </div>
                            </td>
                            
                            <!-- Minggu -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">09:00-15:00</span>
                                    <div class="staff-name">Lia Amelia</div>
                                    <span class="staff-role role-admin">Admin</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">11:00-17:00</span>
                                    <div class="staff-name">Anton Susanto</div>
                                    <span class="staff-role role-director">Direktur</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">10:00-16:00</span>
                                    <div class="staff-name">Citra Ayu</div>
                                    <span class="staff-role role-makeup">Makeup Artist</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


<!-- Legend -->
<div class="role-legend">
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(66, 153, 225, 0.3)"></div>
                    <span>Admin</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(114, 9, 183, 0.3)"></div>
                    <span>Editor</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(48, 209, 88, 0.3)"></div>
                    <span>Photographer</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(255, 159, 10, 0.3)"></div>
                    <span>Videographer</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(255, 55, 95, 0.3)"></div>
                    <span>Makeup Artist</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(191, 90, 242, 0.3)"></div>
                    <span>Designer/Attire</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(0, 199, 190, 0.3)"></div>
                    <span>Admin Editor</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(255, 214, 0, 0.3)"></div>
                    <span>Direktur</span>
                </div>
            </div>
        </div>
    </div>
</div>


    </div>
</div>


<!-- Floating Button -->
<div class="floating-btn">
    <i class="fas fa-plus"></i>
</div><div class="welcome-section">
            <h1>Jadwal Kerja Karyawan</h1>
            <p>Kelola jadwal kerja tim Anda untuk minggu ini</p>
            <a href="#" class="see-all">Ekspor Jadwal <i class="fas fa-download"></i></a>
        </div>


        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">Filter Jadwal</div>
            <div class="filter-controls">
                <div class="date-filter">
                    <label for="start-date">Dari Tanggal:</label>
                    <input type="text" id="start-date" class="date-input" placeholder="Pilih tanggal">
                </div>
                <div class="date-filter">
                    <label for="end-date">Sampai Tanggal:</label>
                    <input type="text" id="end-date" class="date-input" placeholder="Pilih tanggal">
                </div>
                <button class="filter-btn">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
            </div>
        </div>


        <!-- Schedule Table -->
        <div class="schedule-container">
            <div class="schedule-header">
                <h3>Jadwal Mingguan</h3>
                <div class="week-navigation">
                    <button class="week-btn" id="prev-week">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="current-week" id="current-week">3 - 9 September 2025</div>
                    <button class="week-btn" id="next-week">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Senin<br><span class="day-display">01 Sept</span></th>
                            <th>Selasa<br><span class="day-display">02 Sept</span></th>
                            <th>Rabu<br><span class="day-display">03 Sept</span></th>
                            <th>Kamis<br><span class="day-display">04 Sept</span></th>
                            <th>Jumat<br><span class="day-display">05 Sept</span></th>
                            <th>Sabtu<br><span class="day-display">06 Sept</span></th>
                            <th>Minggu<br><span class="day-display">07 Sept</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- Senin -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-16:00</span>
                                    <div class="staff-name">Budi Santoso</div>
                                    <span class="staff-role role-photographer">Photographer</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">09:00-17:00</span>
                                    <div class="staff-name">Sari Dewi</div>
                                    <span class="staff-role role-makeup">Makeup Artist</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">10:00-15:00</span>
                                    <div class="staff-name">Ahmad Fauzi</div>
                                    <span class="staff-role role-videographer">Videographer</span>
                                </div>
                            </td>
                            
                            <!-- Selasa -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-16:00</span>
                                    <div class="staff-name">Ahmad Fauzi</div>
                                    <span class="staff-role role-videographer">Videographer</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">09:00-17:00</span>
                                    <div class="staff-name">Dewi Lestari</div>
                                    <span class="staff-role role-editor">Editor</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">13:00-18:00</span>
                                    <div class="staff-name">Budi Santoso</div>
                                    <span class="staff-role role-photographer">Photographer</span>
                                </div>
                            </td>
                            
                            <!-- Rabu -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-12:00</span>
                                    <div class="staff-name">Rina Wijaya</div>
                                    <span class="staff-role role-admin">Admin</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">09:00-17:00</span>
                                    <div class="staff-name">Joko Prasetyo</div>
                                    <span class="staff-role role-director">Direktur</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">14:00-19:00</span>
                                    <div class="staff-name">Sari Dewi</div>
                                    <span class="staff-role role-makeup">Makeup Artist</span>
                                </div>
                            </td>
                            
                            <!-- Kamis -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">10:00-16:00</span>
                                    <div class="staff-name">Maya Sari</div>
                                    <span class="staff-role role-designer">Designer/Attire</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-17:00</span>
                                    <div class="staff-name">Fajar Nugroho</div>
                                    <span class="staff-role role-lead-editor">Lead Editor</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">12:00-20:00</span>
                                    <div class="staff-name">Ahmad Fauzi</div>
                                    <span class="staff-role role-videographer">Videographer</span>
                                </div>
                            </td>
                            
                            <!-- Jumat -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">09:00-16:00</span>
                                    <div class="staff-name">Citra Ayu</div>
                                    <span class="staff-role role-makeup">Makeup Artist</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">10:00-18:00</span>
                                    <div class="staff-name">Hendra Kurniawan</div>
                                    <span class="staff-role role-photographer">Photographer</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-15:00</span>
                                    <div class="staff-name">Dewi Lestari</div>
                                    <span class="staff-role role-editor">Editor</span>
                                </div>
                            </td>
                            
                            <!-- Sabtu -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">08:00-14:00</span>
                                    <div class="staff-name">Dian Pratama</div>
                                    <span class="staff-role role-videographer">Videographer</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">10:00-17:00</span>
                                    <div class="staff-name">Rizky Maulana</div>
                                    <span class="staff-role role-editor">Editor</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">13:00-19:00</span>
                                    <div class="staff-name">Hendra Kurniawan</div>
                                    <span class="staff-role role-photographer">Photographer</span>
                                </div>
                            </td>
                            
                            <!-- Minggu -->
                            <td>
                                <div class="staff-card">
                                    <span class="staff-time">09:00-15:00</span>
                                    <div class="staff-name">Lia Amelia</div>
                                    <span class="staff-role role-admin">Admin</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">11:00-17:00</span>
                                    <div class="staff-name">Anton Susanto</div>
                                    <span class="staff-role role-director">Direktur</span>
                                </div>
                                <div class="staff-card">
                                    <span class="staff-time">10:00-16:00</span>
                                    <div class="staff-name">Citra Ayu</div>
                                    <span class="staff-role role-makeup">Makeup Artist</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


<!-- Legend -->
<div class="role-legend">
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(66, 153, 225, 0.3)"></div>
                    <span>Admin</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(114, 9, 183, 0.3)"></div>
                    <span>Editor</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(48, 209, 88, 0.3)"></div>
                    <span>Photographer</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(255, 159, 10, 0.3)"></div>
                    <span>Videographer</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(255, 55, 95, 0.3)"></div>
                    <span>Makeup Artist</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(191, 90, 242, 0.3)"></div>
                    <span>Designer/Attire</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(0, 199, 190, 0.3)"></div>
                    <span>Admin Editor</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: rgba(255, 214, 0, 0.3)"></div>
                    <span>Direktur</span>
                </div>
            </div>
        </div>
    </div>
</div>


    </div>
</div>


<!-- Floating Button -->
<div class="floating-btn">
    <i class="fas fa-plus"></i>
</div>