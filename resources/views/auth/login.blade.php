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
            background: linear-gradient(135deg, #d1d1d1, #e9e9e9);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 380px;
        }

        .card {
            border-radius: 16px;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .card-header {
            border-bottom: none;
        }

        .login-title {
            font-weight: 700;
            color: #3b82f6;
        }

        .login-box-msg {
            color: #6b7280;
        }

        .input-group .form-control {
            border-radius: 10px;
            padding: 12px;
        }

        .input-group-text {
            border-radius: 0 10px 10px 0;
            background: #eef2ff;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59,130,246,0.2);
        }

        .btn-primary {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59,130,246,0.4);
        }

        .logo-text {
            font-size: 26px;
            font-weight: 700;
            color: #3b82f6;
        }

        .sub-text {
            font-size: 13px;
            color: #9ca3af;
        }
    </style>
</head>

<body>

<div class="login-box">

    <div class="card p-3">
        <div class="card-header text-center">

            <div class="logo-text">CBT System</div>

        </div>

        <div class="card-body">

            <p class="login-box-msg">Silakan login untuk memulai ujian</p>

            <form action="{{ route('login.authenticate') }}" method="post">
                @csrf

                <div class="input-group mb-3">
                    <input type="text"
                        class="form-control"
                        placeholder="Username"
                        name="username"
                        required>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                        class="form-control"
                        placeholder="Password"
                        name="password"
                        required>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block w-100">
                    <i class="fas fa-sign-in-alt me-1"></i> Login
                </button>

            </form>

        </div>
    </div>

</div>

<!-- JS -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
