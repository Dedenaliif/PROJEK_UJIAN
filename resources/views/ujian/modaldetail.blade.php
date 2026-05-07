<!-- Modal Detail -->
<div class="modal fade"
    id="detailModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow-lg rounded-4">

            {{-- HEADER --}}
            <div class="modal-header border-0 pb-0">

                <div>
                    <h5 class="modal-title fw-bold mb-1">
                        Detail Hasil Ujian
                    </h5>

                    <small class="text-muted">
                        Informasi nilai dan riwayat percobaan siswa
                    </small>
                </div>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            {{-- BODY --}}
            <div class="modal-body pt-3">

                {{-- NAMA --}}
                <div class="text-center mb-4">

                    <div class="avatar avatar-lg mx-auto mb-3">

                        <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="bx bx-user fs-3"></i>
                        </span>

                    </div>

                    <h4 class="fw-bold mb-0" id="modalNama">
                        -
                    </h4>

                </div>

                {{-- DETAIL --}}
                <div class="table-responsive mb-4">

                    <table class="table table-bordered align-middle mb-0">

                        <tbody>

                            <tr>
                                <th width="220" class="bg-light">
                                    Nilai Terbaik
                                </th>

                                <td>
                                    <span class="fw-bold text-primary fs-5"
                                        id="modalNilai"></span>
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-light">
                                    Status
                                </th>

                                <td id="modalStatus"></td>
                            </tr>

                            <tr>
                                <th class="bg-light">
                                    Total Percobaan
                                </th>

                                <td id="modalPercobaan"></td>
                            </tr>

                            <tr>
                                <th class="bg-light">
                                    Percobaan Terbaik
                                </th>

                                <td id="modalTerbaik"></td>
                            </tr>

                        </tbody>

                    </table>

                </div>

                {{-- RIWAYAT --}}
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h6 class="fw-bold mb-0">
                        Riwayat Percobaan
                    </h6>

                    <span class="badge bg-label-primary">
                        History
                    </span>

                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>Percobaan</th>
                                <th>Nilai</th>
                                <th>Status</th>
                            </tr>

                        </thead>

                        <tbody id="modalRiwayat">
                        </tbody>

                    </table>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 pt-0">

                <button type="button"
                    class="btn btn-outline-secondary px-4"
                    data-bs-dismiss="modal">

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>
