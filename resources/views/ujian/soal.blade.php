@extends('dashboard.index')

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
    @endforeach
</div>
@endif

<div class="container">

    {{-- HEADER --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">{{ $ujian->judul }}</h4>
            </div>

            <span class="badge bg-info">
                {{ $soals->count() }}/30 Soal
            </span>
        </div>
    </div>

    {{-- NOTIF --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            @if(isset($edit))
            {{-- ================= EDIT MODE ================= --}}
            <h5>Edit Soal</h5>

            <form action="{{ route('soal.update', $edit->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <textarea name="text_pertanyaan" class="form-control" required>{{ old('text_pertanyaan', $edit->text_pertanyaan) }}</textarea>
                </div>

                @foreach(['A','B','C','D'] as $opsi)
                <div class="input-group mb-2">
                    <span class="input-group-text">{{ $opsi }}</span>
                    <input type="text" name="opsi_{{ strtolower($opsi) }}"
                        class="form-control"
                        value="{{ old('opsi_'.strtolower($opsi), $edit['opsi_'.strtolower($opsi)]) }}" required>

                    <div class="input-group-text">
                        <input type="radio" name="jawaban_benar" value="{{ $opsi }}"
                            {{ $edit->jawaban_benar == $opsi ? 'checked' : '' }}>
                    </div>
                </div>
                @endforeach

                <button class="btn btn-warning mt-3">Update Soal</button>

                <a href="{{ route('soal.create', $ujian->id) }}" class="btn btn-secondary mt-3">
                    Batal
                </a>
            </form>

            @else
            {{-- ================= CREATE MODE ================= --}}
            <h5>Tambah Banyak Soal</h5>

            <form action="{{ route('soal.store', $ujian->id) }}" method="POST">
                @csrf



                <div id="form-container">

                    <div class="soal-item border p-3 mb-3">
                        <h6>Soal 1</h6>

                        <textarea name="text_pertanyaan[]" class="form-control mb-2" required></textarea>

                        @foreach(['A','B','C','D'] as $opsi)
                        <div class="input-group mb-2">
                            <span class="input-group-text">{{ $opsi }}</span>
                            <input type="text" name="opsi_{{ strtolower($opsi) }}[]" class="form-control" required>

                            <div class="input-group-text">
                                <input type="radio" name="jawaban_benar[0]" value="{{ $opsi }}" required>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>

                <button type="button" id="tambah-soal" class="btn btn-success mb-3">
                    + Tambah Soal
                </button>

                <br>

                <button class="btn btn-primary">
                    Simpan Semua Soal
                </button>

            </form>
            @endif

        </div>
    </div>

    <hr>

    {{-- LIST SOAL --}}
    <table class="table table-hover mt-4">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Pertanyaan</th>
                <th>Jawaban</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($soals as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item->text_pertanyaan }}</td>
                <td>
                    <span class="badge bg-success">
                        {{ $item->jawaban_benar }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('soal.edit', [$ujian->id, $item->id]) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('soal.destroy',[$ujian->id, $item->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

{{-- JAVASCRIPT --}}
<script>
let index = 1;

document.getElementById('tambah-soal')?.addEventListener('click', function () {

    if (index >= 30) {
        alert('Maksimal 30 soal');
        return;
    }

    let html = `
    <div class="soal-item border p-3 mb-3">
        <h6>Soal ${index + 1}</h6>

        <textarea name="text_pertanyaan[]" class="form-control mb-2" required></textarea>

        ${['A','B','C','D'].map(opsi => `
            <div class="input-group mb-2">
                <span class="input-group-text">${opsi}</span>
                <input type="text" name="opsi_${opsi.toLowerCase()}[]" class="form-control" required>

                <div class="input-group-text">
                    <input type="radio" name="jawaban_benar[${index}]" value="${opsi}" required>
                </div>
            </div>
        `).join('')}

        <button type="button" class="btn btn-danger btn-sm mt-2 hapus-soal">
            Hapus Soal
        </button>
    </div>
    `;

    document.getElementById('form-container').insertAdjacentHTML('beforeend', html);

    index++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('hapus-soal')) {
        e.target.closest('.soal-item').remove();
        index--;
    }
});
</script>


@endsection
