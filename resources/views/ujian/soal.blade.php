@extends('dashboard.index')

@section('content')

<div class="container-xxl container-p-y">

    {{-- HEADER --}}
    <div class="card mb-4 shadow-sm border-0 rounded-4">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h4 class="fw-bold mb-1">{{ $ujian->judul }}</h4>
                <small class="text-muted">Manajemen Soal</small>
            </div>

            <span class="badge bg-label-info px-3 py-2">
                {{ $soals->count() }} / 30 Soal
            </span>

        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

{{-- FORM --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-4">

        @if(isset($edit))
        {{-- ================= EDIT MODE ================= --}}
        <h5 class="fw-bold mb-3 text-warning">Edit Soal</h5>

        <form action="{{ route('soal.update', $edit->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="fw-semibold">Pertanyaan</label>
                <textarea name="text_pertanyaan"
                    class="form-control"
                    required>{{ old('text_pertanyaan', $edit->text_pertanyaan) }}</textarea>
            </div>

            @foreach(['A','B','C','D'] as $opsi)
            <div class="input-group mb-2">
                <span class="input-group-text fw-bold">{{ $opsi }}</span>

                <input type="text"
                    name="opsi_{{ strtolower($opsi) }}"
                    class="form-control"
                    value="{{ old('opsi_'.strtolower($opsi), $edit['opsi_'.strtolower($opsi)]) }}"
                    required>

                <span class="input-group-text">
                    <input type="radio"
                        name="jawaban_benar"
                        value="{{ $opsi }}"
                        {{ $edit->jawaban_benar == $opsi ? 'checked' : '' }}>
                </span>
            </div>
            @endforeach

            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-warning px-4">Update</button>

                <a href="{{ route('soal.create', $ujian->id) }}"
                    class="btn btn-secondary px-4">
                    Batal
                </a>
            </div>
        </form>

        @else
        {{-- ================= CREATE MODE ================= --}}
        <h5 class="fw-bold mb-3">Tambah Soal</h5>

        <form action="{{ route('soal.store', $ujian->id) }}" method="POST">
        @csrf

        <div id="form-container">
            @php $startIndex = $soals->count(); @endphp

            <div class="soal-item card border-0 shadow-sm mb-3 rounded-3">
                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold">Soal {{ $startIndex + 1 }}</h6>

                        <button type="button"
                            class="btn btn-sm btn-outline-danger hapus-soal">
                            Hapus
                        </button>
                    </div>

                    <textarea name="text_pertanyaan[]"
                        class="form-control mb-3"
                        placeholder="Masukkan pertanyaan..."
                        required></textarea>

                    @foreach(['A','B','C','D'] as $opsi)
                    <div class="input-group mb-2">
                        <span class="input-group-text">{{ $opsi }}</span>

                        <input type="text"
                            name="opsi_{{ strtolower($opsi) }}[]"
                            class="form-control"
                            placeholder="Opsi {{ $opsi }}"
                            required>

                        <span class="input-group-text">
                            <input type="radio"
                                name="jawaban_benar[0]"
                                value="{{ $opsi }}"
                                required>
                        </span>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

        <button type="button" id="tambah-soal" class="btn btn-success mb-3">
            + Tambah Soal
        </button>

        <br>

        <button class="btn btn-primary px-4">
            Simpan Semua
        </button>

        </form>
        @endif

    </div>
</div>
    {{-- TABLE --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-3">

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light text-center">
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
                            <td class="text-center">{{ $i+1 }}</td>

                            <td>{{ $item->text_pertanyaan }}</td>

                            <td class="text-center">
                                <span class="badge bg-success">
                                    {{ $item->jawaban_benar }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('soal.edit', [$ujian->id, $item->id]) }}"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form id="delete-form-{{ $item->id }}"
                                        action="{{ route('soal.destroy',[$ujian->id, $item->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <button
                                        onclick="confirmDelete({{ $item->id }})"
                                        class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let baseIndex = {{ $soals->count() }};

// 🔥 REFRESH NOMOR
function refreshSoal() {
    document.querySelectorAll('.soal-item').forEach((item, i) => {

        item.querySelector('h6').innerText = 'Soal ' + (baseIndex + i + 1);

        item.querySelectorAll('input[type=radio]').forEach(radio => {
            radio.name = `jawaban_benar[${i}]`;
        });
    });
}

// 🔥 TAMBAH SOAL (FIX)
document.getElementById('tambah-soal').addEventListener('click', function () {

    let total = document.querySelectorAll('.soal-item').length;

    if (baseIndex + total >= 30) {
        Swal.fire('Batas!', 'Maksimal 30 soal', 'warning');
        return;
    }

    let index = total;

    let html = `
    <div class="soal-item card border mb-3">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">
                <h6></h6>
                <button type="button" class="btn btn-danger btn-sm hapus-soal">
                    Hapus
                </button>
            </div>

            <textarea name="text_pertanyaan[]" class="form-control mb-3" required></textarea>

            ${['A','B','C','D'].map(opsi => `
            <div class="input-group mb-2">
                <span class="input-group-text">${opsi}</span>
                <input type="text" name="opsi_${opsi.toLowerCase()}[]" class="form-control" required>

                <span class="input-group-text">
                    <input type="radio" name="jawaban_benar[${index}]" value="${opsi}" required>
                </span>
            </div>
            `).join('')}

        </div>
    </div>
    `;

    document.getElementById('form-container').insertAdjacentHTML('beforeend', html);

    refreshSoal();
});

// 🔥 HAPUS SOAL (FIX EVENT DELEGATION)
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('hapus-soal')) {
        e.target.closest('.soal-item').remove();
        refreshSoal();
    }
});

// 🔥 DELETE DATABASE (SWEETALERT)
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin?',
        text: 'Soal akan dihapus permanen',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

refreshSoal();
</script>

@endsection
