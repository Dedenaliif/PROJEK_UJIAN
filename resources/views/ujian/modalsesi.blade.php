{{-- BUTTON SESI --}}
<div class="d-flex flex-wrap gap-2">

    @foreach ($sesis as $sesi)
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
            data-bs-target="#modal-sesi-{{ $item->id }}-{{ $sesi->id }}">
            {{ $sesi->no_sesi }}
            <small class="d-block">{{ $sesi->jam }}</small>
        </button>
    @endforeach

</div>


{{-- MODAL --}}
@foreach ($sesis as $sesi)
    @php
        $terpakai = \App\Models\UjianSiswaSesi::where('ujian_id', $item->id)
            ->where('sesi_id', '!=', $sesi->id)
            ->pluck('siswa_id')
            ->toArray();

        $selected = \App\Models\UjianSiswaSesi::where('ujian_id', $item->id)
            ->where('sesi_id', $sesi->id)
            ->pluck('siswa_id')
            ->toArray();
    @endphp


    <div class="modal fade sesi-modal" id="modal-sesi-{{ $item->id }}-{{ $sesi->id }}" tabindex="-1"
        data-bs-backdrop="static" data-bs-keyboard="false">

        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

            <div class="modal-content border-0 shadow-lg rounded-4">

                <form action="{{ route('ujian.sesi.simpan') }}" method="POST">
                    @csrf

                    <input type="hidden" name="ujian_id" value="{{ $item->id }}">
                    <input type="hidden" name="sesi_id" value="{{ $sesi->id }}">


                    {{-- HEADER --}}
                    <div class="modal-header bg-white border-bottom px-4 py-3">

                        <div>
                            <h5 class="fw-bold mb-1 text-dark">
                                {{ $item->judul }}
                            </h5>

                            <div class="d-flex align-items-center gap-2">

                                <span class="badge bg-primary px-3 py-2">
                                    {{ $sesi->no_sesi }}
                                </span>

                                <span class="badge bg-light text-dark border px-3 py-2">
                                    🕒 {{ $sesi->jam }}
                                </span>

                            </div>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>


                    {{-- BODY --}}
                    <div class="modal-body px-4 py-4">

                        <div class="mb-4">
                            <input type="text" class="form-control search-siswa" placeholder="Cari siswa...">
                        </div>


                        <div class="siswa-scroll">

                            <div class="row g-3 siswa-list">

                                @foreach ($siswas as $siswa)
                                    @if (!in_array($siswa->id, $terpakai) || in_array($siswa->id, $selected))
                                        <div class="col-md-4 siswa-item"
                                            data-name="{{ strtolower($siswa->nama_siswa) }}">

                                            <label
                                                for="siswa-{{ $item->id }}-{{ $sesi->id }}-{{ $siswa->id }}"
                                                class="w-100 mb-0">

                                                <input class="d-none" type="checkbox" name="siswa[]"
                                                    value="{{ $siswa->id }}"
                                                    id="siswa-{{ $item->id }}-{{ $sesi->id }}-{{ $siswa->id }}"
                                                    {{ in_array($siswa->id, $selected) ? 'checked' : '' }}>

                                                <div class="siswa-card">

                                                    <div class="fw-bold text-truncate">
                                                        {{ $siswa->nama_siswa }}
                                                    </div>

                                                    <small class="text-muted d-block mt-1">
                                                        NIS : {{ $siswa->nis ?? '-' }}
                                                    </small>

                                                </div>

                                            </label>

                                        </div>
                                    @endif
                                @endforeach

                            </div>

                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="modal-footer bg-white border-top">

                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-primary px-4">
                            Simpan Sesi
                        </button>

                    </div>

                </form>
            </div>
        </div>
    </div>
@endforeach



<style>
    .sesi-modal {
        z-index: 999999 !important;
    }

    .modal-backdrop {
        z-index: 999998 !important;
    }

    /* mobile safe */
    .modal-dialog {
        margin: 1rem auto;
        max-width: 95%;
    }

    .modal-content {
        max-height: 95vh;
        overflow: hidden;
        border-radius: 18px;
    }

    /* HEADER */
    .modal-header {
        flex-shrink: 0;
    }

    /* BODY */
    .modal-body {
        overflow-y: auto;
    }

    /* FOOTER */
    .modal-footer {
        flex-shrink: 0;
        position: sticky;
        bottom: 0;
        background: #fff;
        z-index: 10;
        padding: 16px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* SCROLL SISWA */
    .siswa-scroll {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 6px;
    }

    /* CARD */
    .siswa-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 18px;
        cursor: pointer;
        background: #fff;
        transition: .25s;
        min-height: 95px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .siswa-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
    }

    input:checked+.siswa-card {
        background: #696cff;
        border-color: #696cff;
        color: #fff;
    }

    input:checked+.siswa-card small {
        color: #fff !important;
    }

    /* MOBILE FIX */
    @media(max-width:768px) {

        .modal-dialog {
            margin: .5rem;
            max-width: 100%;
        }

        .modal-content {
            height: 95vh;
        }

        .modal-body {
            padding: 15px !important;
        }

        .modal-footer {
            padding: 12px !important;
            justify-content: center;
        }

        .modal-footer .btn {
            flex: 1;
            min-width: 120px;
        }

        .siswa-scroll {
            max-height: 50vh;
        }

        .col-md-4 {
            width: 100%;
        }

        .modal-header h5 {
            font-size: 1rem;
        }
    }
</style>



<script>
    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll('.search-siswa').forEach(input => {

            input.addEventListener('keyup', function() {

                let val = this.value.toLowerCase();
                let modal = this.closest('.modal');

                modal.querySelectorAll('.siswa-item').forEach(item => {

                    item.style.display =
                        item.dataset.name.includes(val) ?
                        '' :
                        'none';

                });

            });

        });

    });
</script>
