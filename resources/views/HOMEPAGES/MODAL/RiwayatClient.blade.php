<div class="booking-modal" id="bookingModal-{{ $booking->id }}">
    <div class="booking-modal-backdrop"></div>

    <div class="booking-modal-content">
        <button class="booking-modal-close">&times;</button>

        <h3>Detail Booking</h3>
        <h3 class="modal-section-title">Data Paket</h3>
        <div class="modal-grid">
        
            <div><strong>Kode Pesanan</strong><br>#{{ $booking->kode_pesanan }}</div>
            <div><strong>Status</strong><br>{{ ucfirst($booking->status) }}</div>

            <div><strong>Paket</strong><br>{{ $booking->package->nama_paket ?? '-' }}</div>
            <div><strong>Style</strong><br>{{ $booking->style ?? '-' }}</div>

            <div><strong>Tanggal</strong><br>{{ $booking->photoshoot_date->format('d F Y') }}</div>
            <div><strong>Slot</strong><br>{{ $booking->photoshoot_slot }}</div>

            <div><strong>Extra Slot</strong><br>{{ $booking->extra_photoshoot_slot }}</div>

            <div><strong>Tema Utama</strong><br>{{ $booking->tema_nama }} ({{ $booking->tema_kode }})</div>
            <div><strong>Tema Tambahan</strong><br>{{ $booking->tema2_nama ?? '-' }}</div>

        </div>

        <h3 class="modal-section-title">Data costumer</h3>

        <div class="modal-grid">
            <div><strong>Nama CPP</strong><br>{{ $booking->nama_cpp }}</div>
            <div><strong>Nama CPW</strong><br>{{ $booking->nama_cpw }}</div>

            <div><strong>Email CPP</strong><br>{{ $booking->email_cpp }}</div>
            <div><strong>Email CPW</strong><br>{{ $booking->email_cpw }}</div>

            <div><strong>No CPP</strong><br>{{ $booking->phone_cpp }}</div>
            <div><strong>No CPW</strong><br>{{ $booking->phone_cpw }}</div>
        </div>

        <h3 class="modal-section-title">Harga & Tambahan</h3>

        <div class="modal-grid">   
            <div><strong>Harga Paket</strong><br>{{ 'Rp ' . number_format((int)$booking->package_price, 0, ',', '.') }}</div>
            <div><strong>Total Addon</strong><br>{{ $booking->addons_total_formatted }}</div>

            <div style="grid-column:1/-1">
                <strong>Total</strong><br>
                {{ $booking->grand_total_formatted}}
            </div>
        </div>
    </div>
</div>
