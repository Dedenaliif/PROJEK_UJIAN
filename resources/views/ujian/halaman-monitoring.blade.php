@extends('dashboard.index')

@section('content')
<div class="container-fluid py-4 col-md-12 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Monitoring Ujian: {{ $ujian->judul }}</h2>
        <span class="badge bg-primary px-3 py-2">Ujian {{ ucfirst($ujian->tipe) }}</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Siswa</h6>
                    <h2 class="mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-primary">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Sedang Mengerjakan</h6>
                    <h2 class="mb-0 text-primary">{{ $stats['mengerjakan'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-success">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Selesai</h6>
                    <h2 class="mb-0 text-success">{{ $stats['selesai'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-danger ">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Belum Hadir</h6>
                    <h2 class="mb-0 text-danger">{{ $stats['offline'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 font-weight-bold">Daftar Status Siswa</h6>
        </div>
        <div class="table-responsive">
            <table id="table-kelas" class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Mulai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $siswa)
                        @php
                            $percobaan = $siswa->user->percobaanUjians->first();
                            $jawabanTerkirim = $percobaan ? $percobaan->jawabans->count() : 0;
                            $persen = $totalSoal > 0 ? ($jawabanTerkirim / $totalSoal) * 100 : 0;
                        @endphp
                        <tr>
                            <td>{{ $siswa->nis }}</td>
                            <td>{{ $siswa->nama_siswa }}</td>
                            <td>{{ $siswa->kelas->nama_kelas }}</td>
                            <td>{{ $siswa->jurusan->nama_jurusan }}</td>
                            <td style="width: 200px;">
                                @if($percobaan)
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar {{ $percobaan->status == 'selesai' ? 'bg-success' : 'bg-primary' }}" 
                                             style="width: {{ $persen }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ round($persen) }}% ({{ $jawabanTerkirim }}/{{ $totalSoal }})</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if(!$percobaan)
                                    <span class="badge rounded-pill bg-danger">Offline</span>
                                @elseif($percobaan->status == 'sedang dikerjakan')
                                    <span class="badge rounded-pill bg-primary">Mengerjakan</span>
                                @else
                                    <span class="badge rounded-pill bg-success">Selesai</span>
                                @endif
                            </td>
                            <td>{{ $percobaan ? \Carbon\Carbon::parse($percobaan->waktu_mulai)->format('H:i:s') : '--:--' }}</td>
                            <td>
                                @if($percobaan && $percobaan->status == 'sedang dikerjakan')
                                    <form action="" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger">Selesaikan Paksa</button>
                                    </form>
                                @elseif(!$percobaan)
                                    <button class="btn btn-sm btn-light" disabled>Belum Mulai</button>
                                @else
                                    <button class="btn btn-sm btn-success" disabled>Selesai</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection