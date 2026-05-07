<div class="modal fade" id="modal-tambah-user" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

        <form action="{{ route('user.store') }}" method="POST" class="w-100">
            @csrf

            <div class="modal-content border-0 shadow-lg">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        Tambah User
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body pt-2">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama User
                        </label>

                        <input type="text"
                            name="username"
                            class="form-control"
                            placeholder="Masukkan username"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Role
                        </label>

                        <select name="role"
                            class="form-select"
                            required>

                            <option disabled selected>
                                -- Pilih Role --
                            </option>

                            <option value="admin">Admin</option>
                            <option value="siswa">Siswa</option>
                            <option value="penguji">Penguji</option>
                            <option value="pengawas">Pengawas</option>

                        </select>
                    </div>

                    <input type="hidden"
                        name="password"
                        value="12345678">

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
