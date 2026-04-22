@extends('dashboard.index')

@section('content')

<nav class="bg-white shadow-sm mb-4 border-bottom">
    <div class="container py-4 px-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap">

        {{-- JUDUL --}}
        <div>
            <h4 class="fw-bold text-primary mb-1">{{ $ujian->judul }}</h4>
            <small class="text-muted">
                Ujian {{ ucfirst($ujian->tipe) }} • {{ count($soals) }} Soal
            </small>
        </div>

        {{-- TIMER --}}
        <div class="timer-box text-center px-4 py-3">
            <div class="small text-muted mb-1">Sisa Waktu</div>
            <div class="fw-bold text-danger fs-2" id="timer">00:00:00</div>
        </div>

    </div>

</div>
</nav>




<div class="container">
    <div class="row">

        {{-- SOAL --}}
        <div class="col-md-8">
            <div class="card p-4 shadow-lg border-0">

                <div class="mb-3 d-flex justify-content-between">
                    <span class="badge bg-dark px-3 py-2">
                        Soal <span id="nomor"></span> / {{ count($soals) }}
                    </span>
                </div>

                <h5 id="soalText" class="mb-4 fw-semibold"></h5>

                <div id="opsiContainer"></div>

                <div class="mt-4 d-flex justify-content-between">
                    <button id="prevBtn" class="btn btn-outline-secondary px-4">← Prev</button>
                    <button id="nextBtn" class="btn btn-primary px-4">Next →</button>
                </div>

            </div>
        </div>

        {{-- NAVIGASI --}}
        <div class="col-md-4">
            <div class="card p-4 shadow-lg border-0">

                <h6 class="text-center mb-3 fw-bold">Navigasi Soal</h6>

                <div class="d-flex flex-wrap gap-2 justify-content-center" id="navSoal">
                    @foreach ($soals as $i => $s)
                        <button class="nomor-btn" data-no="{{ $i + 1 }}">
                            {{ $i + 1 }}
                        </button>
                    @endforeach
                </div>

                <hr>

                <form id="formSelesai" method="POST" action="{{ route('ujianstart.selesai', $ujian->id) }}">
                    @csrf
                   <button id="btnSelesai" type="submit" class="btn btn-success w-100 fw-bold">
                        Selesaikan Ujian
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

<style>
.opsi {
    border: 2px solid #eee;
    border-radius: 10px;
    padding: 12px;
    transition: 0.2s;
}

.opsi:hover {
    background: #eef4ff;
    border-color: #0d6efd;
    transform: scale(1.02);
    cursor: pointer;
}

.opsi.active {
    border-color: #0d6efd;
    background: #e7f0ff;
}

.nomor-btn {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    border: none;
    background: #f1f1f1;
    font-weight: bold;
    transition: 0.2s;
}

.nomor-btn:hover {
    transform: scale(1.1);
}

.nomor-btn.active {
    background: #0d6efd;
    color: white;
}

.nomor-btn.done {
    background: #198754;
    color: white;
}

.timer-box {
    background: linear-gradient(135deg, #fff5f5, #ffe3e3);
    border-radius: 14px;
    min-width: 150px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

#timer {
    transition: 0.3s;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let soals = @json($soals);
let jawabanUser = @json($jawabanUser);

let current = {{ $current }};
let total = soals.length;

// 🔥 RENDER SOAL SUPER CEPAT (NO DELAY)
function renderSoal(no) {

    let soal = soals[no - 1];

    $('#soalText').text(soal.text_pertanyaan);
    $('#nomor').text(no);

    let html = '';

    ['a','b','c','d'].forEach(opt => {

        let value = opt.toUpperCase();
        let checked = jawabanUser[soal.id] === value ? 'checked' : '';

        html += `
        <label class="opsi d-block mt-2">
            <input type="radio" class="jawaban-radio me-2"
                name="jawaban"
                data-soal="${soal.id}"
                value="${value}" ${checked}>
            <b>${value}</b>. ${soal['opsi_' + opt]}
        </label>`;
    });

    $('#opsiContainer').html(html);

    current = no;

    updateNavigasi();
}

// 🔥 UPDATE NAVIGASI (LIVE)
function updateNavigasi() {

    $('.nomor-btn').each(function() {

        let no = $(this).data('no');
        let soal = soals[no - 1];

        $(this).removeClass('active done');

        if (jawabanUser[soal.id]) {
            $(this).addClass('done'); // sudah jawab
        }

        if (no == current) {
            $(this).addClass('active');
        }
    });
}

// 🔥 NEXT / PREV
$('#nextBtn').click(() => {
    if (current < total) renderSoal(current + 1);
});

$('#prevBtn').click(() => {
    if (current > 1) renderSoal(current - 1);
});

// 🔥 CLICK NAV
$('.nomor-btn').click(function() {
    renderSoal($(this).data('no'));
});

// 🔥 SAVE + UPDATE REALTIME
$(document).on('change', '.jawaban-radio', function() {

    let jawaban = $(this).val();
    let soalId = $(this).data('soal');

    jawabanUser[soalId] = jawaban;

    updateNavigasi();

    $('.opsi').removeClass('active');
    $(this).closest('.opsi').addClass('active');

    $.post("{{ route('ujianstart.save', $ujian->id) }}", {
        _token: "{{ csrf_token() }}",
        soal_id: soalId,
        jawaban: jawaban
    });
});

// 🔥 INIT
renderSoal(current);

let waktuSelesai = {{ $waktuSelesai->timestamp }};
let sekarang = Math.floor(Date.now() / 1000);
let isSubmitting = false;

function updateTimer() {
    let sisa = waktuSelesai - sekarang;

    if (sisa <= 0) {
        document.getElementById('timer').innerHTML = "00:00:00";

        if (!isSubmitting) {
            isSubmitting = true;
            document.getElementById('formSelesai').submit();
        }
        return;
    }

    let jam = Math.floor(sisa / 3600);
    let menit = Math.floor((sisa % 3600) / 60);
    let detik = sisa % 60;

    document.getElementById('timer').innerHTML =
        String(jam).padStart(2, '0') + ":" +
        String(menit).padStart(2, '0') + ":" +
        String(detik).padStart(2, '0');

    sekarang++;
}

setInterval(updateTimer, 1000);

function updateProgress() {
    let total = soals.length;
    let answered = Object.keys(jawabanUser).length;

    let persen = Math.round((answered / total) * 100);

    $('#progressText').text(persen + '%');
    $('#progressBar').css('width', persen + '%');
}

$(document).on('click', '#btnSelesai', function(e) {

    e.preventDefault(); // 🔥 WAJIB

    Swal.fire({
        title: 'Yakin ingin menyelesaikan ujian?',
        text: "Pastikan semua soal sudah dijawab!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Selesaikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (result.isConfirmed) {

            $('#formSelesai').submit();
        }

    });

});
</script>

@endsection
