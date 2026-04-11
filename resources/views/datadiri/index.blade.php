<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir Data Diri</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .btn-submit {
            background-color: #007bff;
            border: none;
            border-radius: 15px;
            padding: 10px 30px;
            color: #fff;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class=" card form-card border-0 p-4 p-md-5">
                    <h3 class="mb-4 text-primary  fw-bold">Form Data Diri</h3>


                    <form method="post" action="{{ route('datadiri.store') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class=" form-label fw-semibold my-2">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_siswa" placeholder="Masukkan Nama Lengkap">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold my-2">NIS</label>
                                <input type="text" class="form-control" name="nis" placeholder="Masukkan NIS">
                            </div>

                            <div class="col-md-6">
                                <label class=" form-label fw-semibold my-2">Kelas</label>
                                <select name="kelas" id="kelas" class="form-select">
                                    <option selected disabled value="">-- Pilih Kelas --</option>
                                            @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class=" form-label fw-semibold my-2">Jurusan</label>
                                <select name="jurusan" id="jurusan" class="form-select">
                                    <option selected disabled value="">-- Pilih Jurusan --</option>
                                            @foreach ($jurusan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary btn-submit">
                                Submit
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            $(function() {
                bsCustomFileInput.init();
            });
        </script>
</body>

</html>
