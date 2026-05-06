@extends('dashboard.index')

@section('content')

{{-- HEADER --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">

        <div>
            <h5 class="fw-bold mb-1 text-primary">{{ $ujian->judul }}</h5>
            <small class="text-muted">
                {{ ucfirst($ujian->tipe) }} • {{ count($soals) }} Soal
            </small>
        </div>

        <div class="text-end">
            <small class="text-muted">Sisa Waktu</small>
            <h4 class="fw-bold text-danger mb-0" id="timer">00:00:00</h4>
        </div>

    </div>
</div>

<div class="row">

    {{-- SOAL --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="mb-3 d-flex justify-content-between">
                    <span class="badge bg-label-primary px-3 py-2">
                        Soal <span id="nomor"></span> / {{ count($soals) }}
                    </span>
                </div>

                <h5 id="soalText" class="fw-semibold mb-4"></h5>

                <div id="opsiContainer"></div>

                <div class="mt-4 d-flex justify-content-between">
                    <button id="prevBtn" class="btn btn-outline-secondary">
                        ← Sebelumnya
                    </button>
                    <button id="nextBtn" class="btn btn-primary">
                        Selanjutnya →
                    </button>
                </div>

            </div>

        </div>
    </div>

    {{-- NAVIGASI --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-center fw-bold mb-3">Navigasi Soal</h6>

                <div class="d-flex flex-wrap gap-2 justify-content-center" id="navSoal">
                    @foreach ($soals as $i => $s)
                        <button class="nomor-btn btn btn-sm" data-no="{{ $i + 1 }}">
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

{{-- STYLE (UI ONLY) --}}
<style>
/* OPSI */
.opsi {
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    padding: 10px;
    transition: 0.2s;
}

.opsi:hover {
    background: #f5f7ff;
    border-color: #696cff;
    cursor: pointer;
}

.opsi.active {
    border-color: #696cff;
    background: #eef0ff;
}

/* NAV BUTTON */
.nomor-btn {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #f1f1f1;
    font-weight: 600;
}

.nomor-btn:hover {
    transform: scale(1.05);
}

.nomor-btn.active {
    background: #696cff;
    color: #fff;
}

.nomor-btn.done {
    background: #71dd37;
    color: #fff;
}

/* TIMER */
#timer {
    letter-spacing: 1px;
}
</style>

{{-- SCRIPT (TIDAK DIUBAH) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let soals = @json($soals);
let jawabanUser = @json($jawabanUser);

let current = {{ $current }};
let total = soals.length;

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

function updateNavigasi() {

    $('.nomor-btn').each(function() {

        let no = $(this).data('no');
        let soal = soals[no - 1];

        $(this).removeClass('active done');

        if (jawabanUser[soal.id]) {
            $(this).addClass('done');
        }

        if (no == current) {
            $(this).addClass('active');
        }
    });
}

$('#nextBtn').click(() => {
    if (current < total) renderSoal(current + 1);
});

$('#prevBtn').click(() => {
    if (current > 1) renderSoal(current - 1);
});

$('.nomor-btn').click(function() {
    renderSoal($(this).data('no'));
});

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

$(document).on('click', '#btnSelesai', function(e) {

    e.preventDefault();

    Swal.fire({
        title: 'Yakin ingin menyelesaikan ujian?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#formSelesai').submit();
        }
    });

});
</script>

@endsection
