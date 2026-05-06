<div class="modal fade" id="modal-tambah-user" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama User</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option disabled selected>-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="siswa">Siswa</option>
                            <option value="penguji">Penguji</option>
                            <option value="pengawas">Pengawas</option>
                        </select>
                    </div>

                    <input type="hidden" name="password" value="12345678">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
