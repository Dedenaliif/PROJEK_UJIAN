<script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('sneat/assets/js/main.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const menu = document.getElementById("layout-menu");
        const html = document.documentElement;

        // fix: klik di dalam sidebar tidak dianggap klik luar
        menu.addEventListener("click", function(e) {
            if (window.innerWidth < 1200) {
                e.stopPropagation();
            }
        });

        // fix: klik di luar sidebar → close
        document.addEventListener("click", function() {
            if (window.innerWidth < 1200) {
                html.classList.remove("layout-menu-expanded");
            }
        });

    });

    $(document).ready(function() {

       $('.datatable').each(function () {

        if (!$.fn.DataTable.isDataTable(this)) {

            $(this).DataTable({
                responsive: true,
                autoWidth: false,
                scrollX: true,
                pageLength: 10,

                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "←",
                        next: "→"
                    },
                    zeroRecords: "Data tidak ditemukan"
                }
            });

        }

    });

});
</script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
@endif
@if (session('error'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Pemberitahuan',
            text: '{{ session('error') }}',
            // timer: 2000,
            showConfirmButton: 'Oke'
        });
    </script>
@endif
