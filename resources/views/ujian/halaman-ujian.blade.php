@extends('dashboard.index')

@section('content')

<nav class="bg-white shadow-sm py-3 mb-4">
    <div class="container d-flex justify-content-between">
        <h5 class="fw-bold text-primary">{{ $ujian->judul }}</h5>

        <div class="fw-bold text-danger">
            ⏱ <span id="timer">00:00:00</span>
        </div>

        <div>{{ auth()->user()->username }}</div>
    </div>
</nav>

<div class="container">
    <div class="row">

        {{-- SOAL --}}
        <div class="col-md-8">
            <div class="card p-4 shadow-sm">

                <div class="mb-3">
                    <span class="badge bg-secondary">
                        Soal {{ $current }} / {{ count($soals) }}
                    </span>
                </div>

                <h5>{{ $soal->text_pertanyaan }}</h5>

                @foreach(['a','b','c','d'] as $opt)
                    <label class="d-block border rounded p-3 mt-2">
                        <input type="radio"
                               class="jawaban-radio"
                               name="jawaban"
                               data-soal="{{ $soal->id }}"
                               value="{{ strtoupper($opt) }}"
                               {{ isset($jawabanUser[$soal->id]) && $jawabanUser[$soal->id]==strtoupper($opt)?'checked':'' }}>
                        <b>{{ strtoupper($opt) }}</b>. {{ $soal->{'opsi_'.$opt} }}
                    </label>
                @endforeach

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('ujianstart.show', [$ujian->id,'no'=>$current-1]) }}"
                       class="btn btn-secondary {{ $current==1?'disabled':'' }}">
                        ← Sebelumnya
                    </a>

                    <a href="{{ route('ujianstart.show', [$ujian->id,'no'=>$current+1]) }}"
                       class="btn btn-primary {{ $current==count($soals)?'disabled':'' }}">
                        Selanjutnya →
                    </a>
                </div>

            </div>
        </div>

        {{-- NAVIGASI --}}
        <div class="col-md-4">
            <div class="card p-4 shadow-sm">

                <h6 class="text-center">Navigasi</h6>

                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    @foreach($soals as $i=>$s)

                        @php
                            $answered = isset($jawabanUser[$s->id]);
                        @endphp

                        <a href="{{ route('ujianstart.show',[$ujian->id,'no'=>$i+1]) }}"
                           class="btn btn-sm
                           {{ $current==$i+1 ? 'btn-primary' :
                              ($answered ? 'btn-success' : 'btn-outline-secondary') }}">
                           {{ $i+1 }}
                        </a>

                    @endforeach
                </div>

                <hr>

                <form id="formSelesai" method="POST" action="{{ route('ujianstart.selesai',$ujian->id) }}">
                    @csrf
                    <button class="btn btn-success w-100 fw-bold">
                        Selesaikan Ujian
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

{{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $('.jawaban-radio').on('change', function () {

        let jawaban = $(this).val();
        let soalId = $(this).data('soal');

        $.ajax({
            url: "{{ route('ujianstart.save', $ujian->id) }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                soal_id: soalId,
                jawaban: jawaban
            },
            success: function () {
                console.log("✔ tersimpan");
            }
        });

    });
    </script>

    {{-- TIMER --}}
    <script>
    let waktuSelesai = {{ $waktuSelesai->timestamp }};
    let sekarang = {{ now()->timestamp }};

    setInterval(function(){

        sekarang++;

        let sisa = waktuSelesai - sekarang;

        if(sisa <= 0){
            document.getElementById('formSelesai').submit();
            return;
        }

        let jam = Math.floor(sisa/3600);
        let menit = Math.floor((sisa%3600)/60);
        let detik = sisa%60;

        document.getElementById('timer').innerHTML =
            String(jam).padStart(2,'0')+":"+
            String(menit).padStart(2,'0')+":"+
            String(detik).padStart(2,'0');

    },1000);
    </script>

@endsection
