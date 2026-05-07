<div class="modal fade" id="modal-tambah-jurusan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

        <form action="{{ route('jurusan.store') }}" method="POST" class="w-100">
            @csrf

            <div class="modal-content border-0 shadow-lg">

                <div class="modal-header border-0 pb-0">

                    <h5 class="modal-title fw-bold">
                        Tambah Jurusan
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body pt-2">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Nama Jurusan
                        </label>

                        <input type="text"
                            name="nama_jurusan"
                            class="form-control"
                            placeholder="Masukkan nama jurusan"
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

                        Simpan
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>
