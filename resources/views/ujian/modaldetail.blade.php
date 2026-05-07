    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        Detail Hasil Ujian
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <h5 id="modalNama"></h5>
                    </div>

                    <table class="table table-bordered no-dataTable">

                        <tr>
                            <th width="220">Nilai Terbaik</th>
                            <td id="modalNilai"></td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td id="modalStatus"></td>
                        </tr>

                        <tr>
                            <th>Total Percobaan</th>
                            <td id="modalPercobaan"></td>
                        </tr>

                        <tr>
                            <th>Percobaan Terbaik</th>
                            <td id="modalTerbaik"></td>
                        </tr>

                    </table>

                    <hr>

                    <h6 class="fw-bold mb-3">Riwayat Percobaan</h6>

                    <table class="table table-striped">

                        <thead>
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
        </div>
    </div>
