@extends('dashboard.index')
@section('content')
    <nav class="exam-header py-3 mb-4 shadow-sm">
        <div class="container-fluid px-lg-5">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h5 class="mb-0 fw-bold text-primary">Matematika Dasar - Kelas XII</h5>
                </div>
                <div class="col-md-4 text-center">
                    <div class="d-inline-block timer-box">
                        <i class="bi bi-clock-history me-2"></i>Sisa Waktu: <span id="timer">01:29:45</span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span class="me-3 fw-semibold">Budi Santoso</span>
                    <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=random" class="rounded-circle"
                        width="35" alt="Profile">
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-lg-5">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card question-card p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge bg-secondary px-3 py-2">Soal No. 12 dari 40</span>
                        <button class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-flag-fill me-1"></i>Ragu-ragu
                        </button>
                    </div>

                    <h4 class="mb-4 lh-base">Berapakah hasil dari turunan pertama fungsi $f(x) = 3x^2 + 5x - 2$?</h4>

                    <div class="options-list">
                        <input type="radio" class="btn-check" name="options" id="opt1" autocomplete="off">
                        <label class="option-container d-block" for="opt1">
                            <strong>A.</strong> $6x + 5$
                        </label>

                        <input type="radio" class="btn-check" name="options" id="opt2" autocomplete="off">
                        <label class="option-container d-block" for="opt2">
                            <strong>B.</strong> $3x + 5$
                        </label>

                        <input type="radio" class="btn-check" name="options" id="opt3" autocomplete="off">
                        <label class="option-container d-block" for="opt3">
                            <strong>C.</strong> $6x - 2$
                        </label>

                        <input type="radio" class="btn-check" name="options" id="opt4" autocomplete="off">
                        <label class="option-container d-block" for="opt4">
                            <strong>D.</strong> $x^2 + 5$
                        </label>
                    </div>

                    <div class="mt-5 d-flex justify-content-between">
                        <button class="btn btn-outline-secondary px-4 py-2">
                            <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                        </button>
                        <button class="btn btn-primary px-5 py-2 fw-bold">
                            Selanjutnya<i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card question-card p-4">
                    <h6 class="fw-bold mb-3 text-center">Navigasi Soal</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <div class="nav-box answered">1</div>
                        <div class="nav-box answered">2</div>
                        <div class="nav-box answered">3</div>
                        <div class="nav-box flagged">4</div>
                        <div class="nav-box answered">5</div>
                        <div class="nav-box answered">6</div>
                        <div class="nav-box current">7</div>
                        <script>
                            for (let i = 8; i <= 20; i++) {
                                document.write(`<div class="nav-box">${i}</div>`);
                            }
                        </script>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid">
                        <button class="btn btn-success fw-bold py-2" data-bs-toggle="modal" data-bs-target="#finishModal">
                            Selesaikan Ujian
                        </button>
                    </div>
                </div>

                <div class="mt-3 px-2 d-flex justify-content-between small text-muted text-center">
                    <div>
                        <div class="nav-box answered mx-auto mb-1" style="width:20px; height:20px"></div> Terjawab
                    </div>
                    <div>
                        <div class="nav-box flagged mx-auto mb-1" style="width:20px; height:20px"></div> Ragu
                    </div>
                    <div>
                        <div class="nav-box mx-auto mb-1" style="width:20px; height:20px"></div> Belum
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="finishModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-5">
                    <i class="bi bi-exclamation-circle text-warning display-1 mb-4"></i>
                    <h3 class="fw-bold">Selesaikan Ujian?</h3>
                    <p class="text-muted">Pastikan semua jawaban telah diperiksa. Anda tidak dapat kembali setelah menekan
                        tombol konfirmasi.</p>
                    <div class="mt-4 d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success px-4">Ya, Kumpulkan!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
