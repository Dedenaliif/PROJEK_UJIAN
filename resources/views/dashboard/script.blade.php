<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script>
    $('.btn-edit_kelas').click(function() {
        let id = $(this).data('id');
        let nama_kelas = $(this).data('nama_kelas');

        $('#edit-nama_kelas').val(nama_kelas);

        $('#form-edit').attr('action', '/kelas/' + id);
    });
</script>
<script>
    $('.btn-tambah-soal').click(function() {
        let judul = $(this).data('judul');
        let ujianId = $(this).data('id');
        console.log(judul);
        $('#judul_ujian').val(judul);
        $('#form-tambah-soal').attr('action', `/ujian/${ujianId}/soal`);
    });
</script>
@if (isset($ujian))
    <script>
        $('.jawaban-radio').change(function() {
            let jawaban = $(this).val();
            let soalId = $(this).data('soal');

            $.post("{{ route('ujianstart.save', $ujian->id) }}", {
                _token: "{{ csrf_token() }}",
                soal_id: soalId,
                jawaban: jawaban
            });
        });
    </script>
@endif
<script>
    $('.btn-edit_jurusan').click(function() {
        let id = $(this).data('id');
        let nama_jurusan = $(this).data('nama_jurusan');

        $('#edit-nama_jurusan').val(nama_jurusan);

        $('#form-edit').attr('action', '/jurusan/' + id);
    });
</script>
<script>
    $('.btn-edit_user').click(function() {
        let id = $(this).data('id');
        let username = $(this).data('username');
        let role = $(this).data('role');

        $('#edit-nama_user').val(username);
        $('#edit-role').val(role);

        $('#form-edit').attr('action', '/user/' + id);
    });
</script>
<script>
    $("#table-kelas").DataTable();
</script>
