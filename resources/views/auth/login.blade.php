<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ujian Online</title>

    <!-- Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
        body {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        /* Container Utama */
        .login-card-container {
            display: flex;
            width: 600px;
            /* Lebar total */
            max-width: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            /* Agar gambar tidak keluar dari border radius */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        /* Sisi Kiri: Gambar */
        .login-image-side {
            flex: none;
            background-image: url('{{ asset('img/logo.png') }}');
            /* GANTI URL GAMBAR DISINI */
            background-size: contain;
            width: 100px;
            height: 100px;
            background-position: center;
            background-repeat: no-repeat;
            display: none;
            /* Sembunyikan di HP */
        }

        /* Sisi Kanan: Form */
        .login-form-side {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .login-image-side {
                display: block;
                /* Munculkan gambar di Desktop */
            }
        }

        .logo-text {
            font-size: 28px;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 5px;
        }

        .login-box-msg {
            color: #6b7280;
            padding: 0;
            /* margin-bottom: 30px; */
            text-align: left;
        }

        .input-group .form-control {
            border-radius: 10px;
            padding: 25px 15px;
            border: 1px solid #e5e7eb;
        }

        .input-group-text {
            border-radius: 0 10px 10px 0;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: none;
        }

        .btn-primary {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
        .flex {
            display: flex;
            align-items: center;
            background-color: #f9fafb;
            /* border: #6b7280 1px solid; */
            gap: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="login-card-container">
        <!-- Bagian Gambar -->


        <!-- Bagian Form -->
        <div class="login-form-side">
            <div class="flex">
                <div class="login-image-side ">
                    <!-- Overlay opsional jika ingin gambar agak gelap -->
                    {{-- <div style="width: 100%; height: 100%; background: rgba(0,0,0,0.1);"></div> --}}
                </div>
                <div class="text-left">
                    <div class="logo-text">CBT System</div>
                    <p class="login-box-msg">Silakan login untuk memulai ujian</p>
                </div>
            </div>

            <form action="{{ route('login.authenticate') }}" method="post">
                @csrf

                <label class="small font-weight-bold text-muted">Username</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Masukkan username" name="username" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>

                <label class="small font-weight-bold text-muted">Password</label>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Masukkan password" name="password"
                        required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember" class="text-muted small">Remember me</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block shadow-sm">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang
                </button>
            </form>

            <div class="mt-4 text-center">
                <small class="text-muted">Lupa password? Hubungi Admin IT</small>
            </div>
        </div>
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
