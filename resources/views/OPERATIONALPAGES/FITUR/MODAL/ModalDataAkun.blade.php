<div id="modalDataAkun" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalDataAkunTitle">Tambah User</h3>
            <button data-close>&times;</button>
        </div>
        <form id="formUser" method="POST">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST">
            <input type="hidden" id="user-id">
            <input type="text" id="user-name" name="name" class="form-control" placeholder="Nama" required>
            <input type="email" id="user-email" name="email" class="form-control" placeholder="Email" required>
            <select id="user-role" name="role" class="form-control" required>
                <option value="ADMIN">ADMIN</option>
                <option value="DIREKTUR">DIREKTUR</option>
                <option value="CLIENT">CLIENT</option>
            </select>
            <input type="password" id="user-password" name="password" class="form-control" placeholder="Password">
            <div class="modal-footer">
                <button type="button" data-close class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
