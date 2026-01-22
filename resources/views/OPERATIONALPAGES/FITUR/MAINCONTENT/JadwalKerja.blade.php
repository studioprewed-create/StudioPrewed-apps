<div class="page-header">
    <div>
        <h1>Jadwal Kerja karyawan</h1>
        <div class="subtitle">Kelola jadwal kerja karyawan</div>
    </div>
</div>

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

<div class="schedule-container">

    {{-- HEADER --}}
    <div class="schedule-header">
        <h3>Jadwal Mingguan</h3>
        <div class="week-navigation">
            <button class="week-btn" id="prev-week">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="current-week">
                {{ $startOfWeek->translatedFormat('d') }}
                -
                {{ $startOfWeek->copy()->addDays(6)->translatedFormat('d F Y') }}
            </div>

            <button class="week-btn" id="next-week">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="schedule-table">

            {{-- TABLE HEAD (7 HARI) --}}
            <thead>
                <tr>
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $dateObj = $startOfWeek->copy()->addDays($i);
                        @endphp
                        <th>
                            {{ $dateObj->translatedFormat('l') }}<br>
                            <span class="day-display">
                                {{ $dateObj->format('d M') }}
                            </span>
                        </th>
                    @endfor
                </tr>
            </thead>

            {{-- TABLE BODY --}}
            <tbody>
                <tr>
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $dateKey = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
                            $dayBookings = $bookingsByDate[$dateKey] ?? collect();
                        @endphp

                        <td>
                            @forelse ($dayBookings as $booking)

                                <div class="staff-card">

                                    {{-- KODE PESANAN --}}
                                    <div class="staff-time">
                                        {{ $booking->kode_pesanan }}
                                    </div>

                                    {{-- NAMA GABUNGAN --}}
                                    <div class="staff-name">
                                        {{ $booking->display_nama_gabungan }}
                                    </div>

                                    {{-- SLOT --}}
                                    <div class="staff-slot">
                                        {{ $booking->photoshoot_slot }}
                                    </div>

                                    {{-- ROLE KARYAWAN --}}
                                    <div class="staff-roles">

                                        @if($booking->skemaKerja?->fotografer)
                                            <span class="staff-role role-photographer">
                                                Fotografer:
                                                {{ $booking->skemaKerja->fotografer->nama_lengkap }}
                                            </span>
                                        @endif

                                        @if($booking->skemaKerja?->videografer)
                                            <span class="staff-role role-videographer">
                                                Videografer:
                                                {{ $booking->skemaKerja->videografer->nama_lengkap }}
                                            </span>
                                        @endif

                                        @if($booking->skemaKerja?->editor)
                                            <span class="staff-role role-editor">
                                                Editor:
                                                {{ $booking->skemaKerja->editor->nama_lengkap }}
                                            </span>
                                        @endif

                                        @if($booking->skemaKerja?->makeup)
                                            <span class="staff-role role-makeup">
                                                Makeup:
                                                {{ $booking->skemaKerja->makeup->nama_lengkap }}
                                            </span>
                                        @endif

                                        @if($booking->skemaKerja?->attire)
                                            <span class="staff-role role-attire">
                                                Attire:
                                                {{ $booking->skemaKerja->attire->nama_lengkap }}
                                            </span>
                                        @endif

                                    </div>
                                </div>

                            @empty
                                <span class="text-muted">Tidak ada booking</span>
                            @endforelse
                        </td>
                    @endfor
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

<div class="floating-btn">
    <i class="fas fa-plus"></i>
</div>