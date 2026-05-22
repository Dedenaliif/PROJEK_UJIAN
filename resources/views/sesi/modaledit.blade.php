<div class="modal fade" id="modal-edit">

    <div class="modal-dialog modal-dialog-centered">

        <form id="form-edit" method="POST" class="w-100">

            @csrf
            @method('PUT')

            <div class="modal-content shadow-lg border-0">

                <div class="modal-header">
                    <h5 class="fw-bold">Edit Sesi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>No Sesi</label>
                        <input type="number" name="no_sesi" id="edit-no_sesi" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="edit-jam_mulai" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="edit-jam_selesai" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
