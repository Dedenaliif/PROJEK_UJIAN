@extends('dashboard.index')

@section('content')
    <nav class="exam-header py-3 mb-4 shadow-sm">
        <div class="container-fluid px-lg-5">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <h5 class="mb-0 fw-bold text-primary">{{ $ujian->judul }}</h5>
                </div>
                <div class="col-md-4 text-center">
                    <div class="d-inline-block ti                                  <i class="bi bi-clock-history me-2">
                        </i>Sisa Waktu: <span id="timer">00:00:00</span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span class="me-3 fw-semibold">{{ auth()->user()->username }}</span>
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
                        <span class="badge bg-secondary px-3 py-2"> Soal No. {{ $current }} dari
                            {{ count($soals) }}</span>
                        <button class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-flag-fill me-1"></i>Ragu-ragu
                        </button>
                    </div>

                    <h4 class="mb-4 lh-base">{{ $soal->text_pertanyaan }}</h4>
                    @foreach (['a', 'b', 'c', 'd'] as $opt)
                        <input type="radio" class="btn-check jawaban-radio" name="jawaban" data-soal="{{ $soal->id }}"
                            value="{{ strtoupper($opt) }}" id="opt{{ $opt }}"
                            {{ isset($jawabanUser[$soal->id]) && $jawabanUser[$soal->id] == strtoupper($opt) ? 'checked' : '' }}>

                        <label class="option-container d-block" for="opt{{ $opt }}">
                            <strong>{{ strtoupper($opt) }}.</strong>
                            {{ $soal->{'opsi_' . $opt} }}
                        </label>
                    @endforeach
                    <div class="mt-5 d-flex justify-content-between">

                        {{-- Sebelumnya --}}
                        <a href="{{ route('ujianstart.show', [$ujian->id, 'no' => $current - 1]) }}"
                            class="btn btn-outline-secondary px-4 py-2 {{ $current == 1 ? 'disabled' : '' }}">
                            <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                        </a>

                        {{-- Selanjutnya --}}
                        <a href="{{ route('ujianstart.show', [$ujian->id, 'no' => $current + 1]) }}"
                            class="btn btn-primary {{ $current == count($soals) ? 'disabled' : '' }}">
                            Selanjutnya
                        </a>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card question-card p-4">
                    <h6 class="fw-bold mb-3 text-center">Navigasi Soal</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @foreach ($soals as $i => $s)
                            <a href="{{ route('ujianstart.show', [$ujian->id, 'no' => $i + 1]) }}"
                                class="nav-box {{ $current == $i + 1 ? 'current' : '' }}">
                                {{ $i + 1 }}
                            </a>
                        @endforeach
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
                        <form action="{{ route('ujianstart.selesai', $ujian->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-success px-4">Ya, Kumpulkan!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $('.jawaban-radio').change(function() {
            let jawaban = $(this).val();
            let soalId = $(this).data('soal');

            $.post("{{ route('ujianstart.save', $ujian->id) }}", {
                _token: "{{ csrf_token() }}",
                soal_id: soalId,
                jawaban: jawaban
            });
        });
    </script>
    @if (isset($waktuSelesai))
        <script>
            let waktuSelesai = {{ $waktuSelesai->timestamp }};
            let waktuServer = {{ now()->timestamp }};
        </script>
    @endif
    <script>
        let interval;

        function startTimer() {
            let timerElement = document.getElementById('timer');

            interval = setInterval(() => {
                waktuServer++;

                let sisa = waktuSelesai - waktuServer;

                if (sisa <= 0) {
                    clearInterval(interval); // 🔥 stop timer
                    timerElement.innerHTML = "00:00:00";

                    document.querySelector('form[action="{{ route('ujianstart.selesai', $ujian->id) }}"]')
                        .submit();
                    return;
                }

                let jam = Math.floor(sisa / 3600);
                let menit = Math.floor((sisa % 3600) / 60);
                let detik = sisa % 60;

                timerElement.innerHTML =
                    String(jam).padStart(2, '0') + ":" +
                    String(menit).padStart(2, '0') + ":" +
                    String(detik).padStart(2, '0');

            }, 1000);
        }

        startTimer();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
