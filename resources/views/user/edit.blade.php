<div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama User</label>
                        <input type="text" name="username" id="edit-nama_user" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                            <select name="role" id="edit-role" class="form-control">
                                <option selected disabled value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="siswa">Siswa</option>
                                <option value="penguji">Penguji</option>
                                <option value="pengawas">Pengawas</option>
                            </select>
                    </div>
                    <input type="hidden" name="password" value="12345678">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

            </div>
        </form>
    </div>
</div>
