<div class="modal fade" id="modal-edit" tabindex="-1">
    <div class="modal-dialog">
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama User</label>
                        <input type="text" name="username" id="edit-username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="edit-role" class="form-select" required>
                            <option disabled>-- Pilih Role --</option>
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
                        Update
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
