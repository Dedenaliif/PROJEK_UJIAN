<div class="modal fade" id="modal-tambah-sesi">

    <div class="modal-dialog modal-dialog-centered">

        <form action="{{ route('sesi.store') }}" method="POST" class="w-100">
            @csrf

            <div class="modal-content shadow-lg border-0">

                <div class="modal-header">
                    <h5 class="fw-bold">Tambah Sesi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>No Sesi</label>
                        <input type="number" name="no_sesi" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
