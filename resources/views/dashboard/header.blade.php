  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UJIAN ONLINE</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">



  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
      body {
          font-family: 'Inter', sans-serif;
          background-color: #f4f7f6;
      }

      .exam-header {
          background-color: #ffffff;
          border-bottom: 2px solid #dee2e6;
          sticky-top: 0;
          z-index: 1020;
      }

      .timer-box {
          background-color: #f8d7da;
          color: #842029;
          padding: 5px 15px;
          border-radius: 8px;
          font-weight: bold;
      }

      .question-card {
          border: none;
          border-radius: 12px;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      }

      .nav-box {
          width: 40px;
          height: 40px;
          display: flex;
          align-items: center;
          justify-content: center;
          border: 1px solid #dee2e6;
          border-radius: 8px;
          cursor: pointer;
          font-size: 0.9rem;
          transition: 0.3s;
      }

      .nav-box:hover {
          background-color: #e9ecef;
      }

      .nav-box.answered {
          background-color: #198754;
          color: white;
          border-color: #198754;
      }

      .nav-box.current {
          border: 2px solid #0d6efd;
          color: #0d6efd;
          font-weight: bold;
      }

      .nav-box.flagged {
          background-color: #ffc107;
          color: black;
          border-color: #ffc107;
      }

      .option-container {
          border: 1px solid #dee2e6;
          border-radius: 10px;
          padding: 12px 15px;
          margin-bottom: 10px;
          cursor: pointer;
          transition: 0.2s;
      }

      .option-container:hover {
          background-color: #f1f8ff;
          border-color: #0d6efd;
      }

      .btn-check:checked+.option-container {
          background-color: #e7f1ff;
          border-color: #0d6efd;
      }
  </style>
  <style>
      body {
          font-family: 'Inter', sans-serif;
          background-color: #f4f7f6;
      }

      .exam-header {
          background-color: #ffffff;
          border-bottom: 2px solid #dee2e6;
          sticky-top: 0;
          z-index: 1020;
      }

      .timer-box {
          background-color: #f8d7da;
          color: #842029;
          padding: 5px 15px;
          border-radius: 8px;
          font-weight: bold;
      }

      .question-card {
          border: none;
          border-radius: 12px;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      }

      .nav-box {
          width: 40px;
          height: 40px;
          display: flex;
          align-items: center;
          justify-content: center;
          border: 1px solid #dee2e6;
          border-radius: 8px;
          cursor: pointer;
          font-size: 0.9rem;
          transition: 0.3s;
      }

      .nav-box:hover {
          background-color: #e9ecef;
      }

      .nav-box.answered {
          background-color: #198754;
          color: white;
          border-color: #198754;
      }

      .nav-box.current {
          border: 2px solid #0d6efd;
          color: #0d6efd;
          font-weight: bold;
      }

      .nav-box.flagged {
          background-color: #ffc107;
          color: black;
          border-color: #ffc107;
      }

      .option-container {
          border: 1px solid #dee2e6;
          border-radius: 10px;
          padding: 12px 15px;
          margin-bottom: 10px;
          cursor: pointer;
          transition: 0.2s;
      }

      .option-container:hover {
          background-color: #f1f8ff;
          border-color: #0d6efd;
      }

      .btn-check:checked+.option-container {
          background-color: #e7f1ff;
          border-color: #0d6efd;
      }
  </style>
