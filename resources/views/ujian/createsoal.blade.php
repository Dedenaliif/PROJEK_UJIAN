
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
    {{-- {{ $ujian ?? 'tidak ada' }} --}}
    <div class="modal fade" id="modal-tambah-pertanyaan">

        <div class="modal-dialog">
            <form id="form-tambah-soal" method="POST" >
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Pertanyaan</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Ujian</label>
                            <input type="text" id="judul_ujian"  class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Skor</label>
                            <input type="number" name="skor" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Pertanyaan</label>
                            <textarea name="text_pertanyaan" class="form-control" required></textarea>
                        </div>

                        <!-- A -->
                        <div class="input-group mb-2">
                            <span class="input-group-text">A</span>
                            <input type="text" name="opsi_a" class="form-control" required>
                            <div class="input-group-text">
                                <input type="radio" name="jawaban_benar" value="A" required>
                            </div>
                        </div>

                        <!-- B -->
                        <div class="input-group mb-2">
                            <span class="input-group-text">B</span>
                            <input type="text" name="opsi_b" class="form-control" required>
                            <div class="input-group-text">
                                <input type="radio" name="jawaban_benar" value="B">
                            </div>
                        </div>

                        <!-- C -->
                        <div class="input-group mb-2">
                            <span class="input-group-text">C</span>
                            <input type="text" name="opsi_c" class="form-control" required>
                            <div class="input-group-text">
                                <input type="radio" name="jawaban_benar" value="C">
                            </div>
                        </div>

                        <!-- D -->
                        <div class="input-group mb-2">
                            <span class="input-group-text">D</span>
                            <input type="text" name="opsi_d" class="form-control" required>
                            <div class="input-group-text">
                                <input type="radio" name="jawaban_benar" value="D">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif --}}

