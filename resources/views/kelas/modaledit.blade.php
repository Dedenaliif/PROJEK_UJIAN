<div class="modal fade" id="modal-edit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

        <form id="form-edit" method="POST" class="w-100">
            @csrf
            @method('PUT')

            <div class="modal-content border-0 shadow-lg">

                <div class="modal-header border-0 pb-0">

                    <h5 class="modal-title fw-bold">
                        Edit Kelas
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body pt-2">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Nama Kelas
                        </label>

                        <input type="text"
                            name="nama_kelas"
                            id="edit-nama_kelas"
                            class="form-control"
                            required>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">

                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-primary">

                        Update
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>
