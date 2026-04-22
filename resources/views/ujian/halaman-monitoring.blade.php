@extends('dashboard.index')

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Monitoring Ujian</h4>
            <small class="text-muted">{{ $ujian->judul }}</small>
        </div>

        <span class="badge bg-primary px-3 py-2">
            {{ ucfirst($ujian->tipe) }}
        </span>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4 text-center">

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <small class="text-muted">Total</small>
                    <h3 id="statTotal">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <small class="text-muted text-primary">Mengerjakan</small>
                    <h3 id="statKerja" class="text-primary">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <small class="text-muted text-success">Selesai</small>
                    <h3 id="statSelesai" class="text-success">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <small class="text-muted text-danger">Offline</small>
                    <h3 id="statOffline" class="text-danger">0</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold">Status Siswa (Realtime)</h6>
        </div>

        <div class="table-responsive">
           <table id="monitoringTable" class="table table-hover align-middle">

                <thead class="table-dark text-center">
                    <tr>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th width="200">Progress</th>
                        <th>Status</th>
                        <th>Mulai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
{{-- SCRIPT --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let table;

$(document).ready(function() {

    table = $('#monitoringTable').DataTable({
        processing: false,
        ajax: {
            url: "{{ route('monitoring.data', $ujian->id) }}",
              dataSrc: function(res) {

                // 🔥 HITUNG STATISTIK DI SINI
                let total = 0;
                let kerja = 0;
                let selesai = 0;
                let offline = 0;

                res.data.forEach(s => {
                    total++;

                    if (s.status === 'offline') {
                        offline++;
                    } else if (s.status === 'sedang dikerjakan') {
                        kerja++;
                    } else {
                        selesai++;
                    }
                });

                // 🔥 UPDATE KE UI
                $('#statTotal').text(total);
                $('#statKerja').text(kerja);
                $('#statSelesai').text(selesai);
                $('#statOffline').text(offline);

                return res.data; // WAJIB return
            }
        },
        columns: [

            { data: 'nis' },
            { data: 'nama' },
            { data: 'kelas' },
            { data: 'jurusan' },

            // PROGRESS
            {
                data: null,
                render: function(data) {

                    if (data.status === 'offline') {
                        return '<span class="text-muted">-</span>';
                    }

                    return `
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar ${data.status === 'selesai' ? 'bg-success' : 'bg-primary'}"
                            style="width:${data.persen}%"></div>
                    </div>
                    <small>${data.persen}%</small>`;
                }
            },

            // STATUS
            {
                data: 'status',
                render: function(status) {

                    if (status === 'offline')
                        return '<span class="badge bg-danger">Offline</span>';

                    if (status === 'sedang dikerjakan')
                        return '<span class="badge bg-primary">Mengerjakan</span>';

                    return '<span class="badge bg-success">Selesai</span>';
                }
            },

            // WAKTU
            {
                data: 'mulai',
                render: function(val) {
                    return val ? val : '--:--';
                }
            },

            // AKSI
            {
                data: null,
                render: function(data) {

                    if (data.status === 'sedang dikerjakan') {
                        return `
                        <button class="btn btn-sm btn-outline-danger stop-btn"
                            data-id="${data.percobaan_id}">
                            Stop
                        </button>`;
                    }

                    if (data.status === 'offline') {
                        return '<span class="text-muted">-</span>';
                    }

                    return '<span class="badge bg-success">✔</span>';
                }
            }

        ],
        paging: true,
        searching: true,
        ordering: true,
        pageLength: 10
    });

    // 🔥 AUTO REFRESH TANPA HANCURKAN TABLE
    setInterval(function() {
        table.ajax.reload(null, false); // false = tidak reset halaman
    }, 3000);
});


// 🔥 FORCE STOP
$(document).on('click', '.stop-btn', function() {

    let id = $(this).data('id');

    if(confirm('Yakin hentikan ujian siswa?')) {
        $.post("/force-stop/" + id, {
            _token: "{{ csrf_token() }}"
        }, function() {
            table.ajax.reload(null, false);
        });
    }
});
</script>
@endsection
