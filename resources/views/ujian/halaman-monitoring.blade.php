@extends('dashboard.index')

@section('content')
<div class="container-fluid py-4 px-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1">Monitoring Ujian</h3>
            <small class="text-muted">{{ $ujian->judul }}</small>
        </div>

        <span class="badge bg-primary px-4 py-2 fs-6 shadow-sm">
            {{ ucfirst($ujian->tipe) }}
        </span>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-4 mb-4 text-center">

        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0">
                <div class="card-body py-4">
                    <small>Total Peserta</small>
                    <h2 id="statTotal" class="fw-bold mt-2">0</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0 border-start border-primary border-4">
                <div class="card-body py-4">
                    <small class="text-primary">Mengerjakan</small>
                    <h2 id="statKerja" class="fw-bold text-primary mt-2">0</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0 border-start border-success border-4">
                <div class="card-body py-4">
                    <small class="text-success">Selesai</small>
                    <h2 id="statSelesai" class="fw-bold text-success mt-2">0</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card shadow-sm border-0 border-start border-danger border-4">
                <div class="card-body py-4">
                    <small class="text-danger">Offline</small>
                    <h2 id="statOffline" class="fw-bold text-danger mt-2">0</h2>
                </div>
            </div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <h6 class="mb-0 fw-bold">Status Siswa (Realtime)</h6>
        </div>

        <div class="card-body p-4">

            <div class="table-responsive">
                <table id="monitoringTable" class="table table-hover align-middle w-100 datatable">

                    <thead class="table-light text-center">
                        <tr>
                            <th>NIS</th>
                            <th class="text-start">Nama</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Percobaan</th>
                            <th width="220">Progress</th>
                            <th>Status</th>
                            <th>Mulai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody></tbody>

                </table>
            </div>

        </div>
    </div>

</div>

{{-- STYLE TAMBAHAN --}}
<style>
.stat-card {
    border-radius: 14px;
    transition: 0.2s;
}
.stat-card:hover {
    transform: translateY(-4px);
}

.table td, .table th {
    vertical-align: middle;
    padding: 14px 12px;
}

.progress {
    border-radius: 10px;
    background: #f1f1f1;
}

.progress-bar {
    border-radius: 10px;
}
</style>

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
        destroy: true,
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
            { data:'percobaan' },

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
