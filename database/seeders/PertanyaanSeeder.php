<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pertanyaan;
use App\Models\Ujian;

class PertanyaanSeeder extends Seeder
{
    public function run()
    {
        $ujians = Ujian::all();

        foreach ($ujians as $ujian) {

            // SOAL WORD
            if ($ujian->tipe == 'word') {

                $soals = [
                    [
                        'text' => 'Fungsi utama tombol pintas Ctrl+B pada Microsoft Word adalah ...',
                        'a' => 'Menyimpan dokumen',
                        'b' => 'Menebalkan teks',
                        'c' => 'Mencetak dokumen',
                        'd' => 'Membuka dokumen baru',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Menu yang digunakan untuk mengubah orientasi halaman dari portrait menjadi landscape adalah ...',
                        'a' => 'Home > Paragraph',
                        'b' => 'Insert > Pages',
                        'c' => 'Layout > Orientation',
                        'd' => 'Review > Language',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Perintah Save As digunakan untuk ...',
                        'a' => 'Menghapus dokumen lama',
                        'b' => 'Menyimpan dokumen dengan nama, lokasi, atau format berbeda',
                        'c' => 'Mencetak dokumen secara langsung',
                        'd' => 'Mengunci dokumen agar tidak bisa dibuka',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Garis merah bergelombang di bawah kata pada Microsoft Word biasanya menunjukkan ...',
                        'a' => 'Kemungkinan kesalahan ejaan',
                        'b' => 'Teks sedang dicetak tebal',
                        'c' => 'Teks sudah diberi hyperlink',
                        'd' => 'Paragraf memiliki spasi ganda',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Untuk membuat tabel di Microsoft Word, menu yang paling tepat digunakan adalah ...',
                        'a' => 'Design > Themes',
                        'b' => 'Review > Comments',
                        'c' => 'Insert > Table',
                        'd' => 'Layout > Margins',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Perataan teks yang membuat sisi kiri dan kanan paragraf tampak rata disebut ...',
                        'a' => 'Align Left',
                        'b' => 'Align Center',
                        'c' => 'Align Right',
                        'd' => 'Justify',
                        'jawaban' => 'D'
                    ],
                    [
                        'text' => 'Fungsi Format Painter adalah ...',
                        'a' => 'Menghapus halaman kosong',
                        'b' => 'Menyalin format teks atau objek ke bagian lain',
                        'c' => 'Mengubah ukuran kertas',
                        'd' => 'Menyisipkan gambar dari internet',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Header dan Footer digunakan untuk ...',
                        'a' => 'Menampilkan informasi berulang di bagian atas atau bawah halaman',
                        'b' => 'Membuat tabel otomatis',
                        'c' => 'Mengubah bahasa dokumen',
                        'd' => 'Menghapus komentar reviewer',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Fitur yang digunakan untuk membuat daftar berpoin adalah ...',
                        'a' => 'Mail Merge',
                        'b' => 'Word Count',
                        'c' => 'Bullets',
                        'd' => 'Track Changes',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Jika ingin mengganti kata "siswa" menjadi "peserta didik" di seluruh dokumen, fitur yang tepat adalah ...',
                        'a' => 'Spelling and Grammar',
                        'b' => 'Translate',
                        'c' => 'Page Break',
                        'd' => 'Find and Replace',
                        'jawaban' => 'D'
                    ],
                    [
                        'text' => 'Seorang guru ingin memberi jarak 6 pt setelah setiap paragraf agar naskah lebih rapi. Pengaturan yang digunakan adalah ...',
                        'a' => 'Font Size',
                        'b' => 'Paragraph Spacing After',
                        'c' => 'Page Color',
                        'd' => 'Text Highlight Color',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Agar daftar isi dapat dibuat otomatis, judul bab dan subbab sebaiknya diberi format ...',
                        'a' => 'Bold manual',
                        'b' => 'Underline manual',
                        'c' => 'Heading Styles',
                        'd' => 'Text Box',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Saat memperbesar gambar agar bentuknya tidak berubah, langkah yang paling tepat adalah ...',
                        'a' => 'Menarik handle sudut gambar atau mengaktifkan Lock Aspect Ratio',
                        'b' => 'Menarik handle samping gambar saja',
                        'c' => 'Mengubah warna gambar menjadi hitam putih',
                        'd' => 'Memotong gambar dengan Crop',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Teks yang disalin dari internet memiliki font, warna, dan ukuran tidak seragam. Cara paling efektif untuk merapikannya adalah ...',
                        'a' => 'Menghapus semua teks lalu mengetik ulang',
                        'b' => 'Mencetak dokumen terlebih dahulu',
                        'c' => 'Mengubah ukuran kertas',
                        'd' => 'Menggunakan Clear Formatting lalu menerapkan Styles yang konsisten',
                        'jawaban' => 'D'
                    ],
                    [
                        'text' => 'Dalam kerja kelompok, dosen ingin melihat perubahan yang dilakukan setiap mahasiswa pada dokumen. Fitur yang tepat adalah ...',
                        'a' => 'WordArt',
                        'b' => 'Track Changes',
                        'c' => 'Watermark',
                        'd' => 'Page Border',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Untuk menyisipkan catatan kaki pada kutipan atau istilah tertentu, menu yang digunakan adalah ...',
                        'a' => 'References > Insert Footnote',
                        'b' => 'Insert > Shapes',
                        'c' => 'View > Zoom',
                        'd' => 'Home > Styles',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Sampul dokumen tidak ingin diberi nomor halaman, sedangkan halaman pendahuluan dimulai dari nomor 1. Solusi yang paling tepat adalah ...',
                        'a' => 'Menghapus nomor halaman satu per satu',
                        'b' => 'Mengubah seluruh dokumen menjadi landscape',
                        'c' => 'Menggunakan section break dan mengatur start page numbering',
                        'd' => 'Menyisipkan tabel di setiap halaman',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Untuk membuat surat undangan dengan isi sama tetapi nama penerima berbeda-beda, fitur yang paling sesuai adalah ...',
                        'a' => 'Mail Merge',
                        'b' => 'Caption',
                        'c' => 'SmartArt',
                        'd' => 'Thesaurus',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Fitur yang tepat untuk membuat tampilan naskah seperti koran dengan beberapa kolom adalah ...',
                        'a' => 'Insert Table',
                        'b' => 'Layout > Columns',
                        'c' => 'Review > Protect',
                        'd' => 'References > Bibliography',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Untuk membuat baris tanda tangan berada rapi di sisi kanan tanpa menekan spasi berulang kali, sebaiknya menggunakan ...',
                        'a' => 'Font Color',
                        'b' => 'Tab stop atau pengaturan paragraph alignment',
                        'c' => 'Screenshot',
                        'd' => 'Equation',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Sebuah laporan memiliki banyak judul bab dengan format yang tidak konsisten. Penulis juga membutuhkan daftar isi otomatis. Strategi terbaik adalah ...',
                        'a' => 'Menerapkan Heading Styles pada judul lalu membuat atau memperbarui Table of Contents',
                        'b' => 'Menebalkan semua judul secara manual lalu mengetik daftar isi sendiri',
                        'c' => 'Mengubah semua teks menjadi huruf kapital',
                        'd' => 'Menyisipkan gambar daftar isi dari dokumen lain',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Dalam dokumen portrait, terdapat satu tabel lebar yang harus ditampilkan landscape tanpa mengubah halaman lain. Langkah paling tepat adalah ...',
                        'a' => 'Mengubah margin seluruh dokumen menjadi sangat kecil',
                        'b' => 'Menambahkan section break sebelum dan sesudah tabel, lalu mengubah orientasi section tersebut menjadi landscape',
                        'c' => 'Memperkecil font semua halaman menjadi 6 pt',
                        'd' => 'Mengubah tabel menjadi gambar agar tidak bisa diedit',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Gambar pada dokumen sering bergeser ketika teks diedit. Agar posisi gambar lebih stabil, pengaturan yang perlu diperhatikan adalah ...',
                        'a' => 'Menghapus semua paragraf di sekitar gambar',
                        'b' => 'Memperbesar ukuran gambar',
                        'c' => 'Mengatur Wrap Text dan posisi gambar sesuai kebutuhan',
                        'd' => 'Mengganti gambar dengan tabel',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Seorang editor ingin memberi saran tanpa langsung mengubah isi naskah, dan penulis tetap dapat melihat perubahan yang diusulkan. Kombinasi fitur yang tepat adalah ...',
                        'a' => 'Page Color dan Watermark',
                        'b' => 'Columns dan Hyphenation',
                        'c' => 'Mail Merge dan Envelope',
                        'd' => 'Comments dan Track Changes',
                        'jawaban' => 'D'
                    ],
                    [
                        'text' => 'Penulis ingin nomor gambar dan tabel selalu otomatis berubah ketika ada gambar baru ditambahkan. Fitur yang paling tepat adalah ...',
                        'a' => 'Insert Caption dan Cross-reference',
                        'b' => 'WordArt dan Text Box',
                        'c' => 'Manual numbering dengan mengetik angka',
                        'd' => 'Find and Replace',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Sebuah program studi ingin semua surat resmi memiliki format kop, margin, font, dan style yang sama. Solusi paling efisien adalah ...',
                        'a' => 'Meminta setiap orang menyalin dokumen lama secara manual',
                        'b' => 'Mengirim gambar kop surat lewat pesan singkat',
                        'c' => 'Membuat template Word dengan style baku',
                        'd' => 'Menyimpan dokumen dalam format gambar',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'File Word menjadi sangat besar karena banyak gambar resolusi tinggi. Tindakan yang paling tepat agar ukuran file lebih ringan adalah ...',
                        'a' => 'Mengubah seluruh teks menjadi italic',
                        'b' => 'Menggunakan Compress Pictures dan memilih resolusi yang sesuai',
                        'c' => 'Menghapus semua nomor halaman',
                        'd' => 'Mengubah bahasa proofing',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Guru membuat formulir Word yang hanya boleh diisi pada bagian tertentu, sedangkan teks instruksi tidak boleh diubah. Fitur yang paling sesuai adalah ...',
                        'a' => 'Restrict Editing dengan form fields atau content controls',
                        'b' => 'Word Count',
                        'c' => 'Page Color',
                        'd' => 'Text Effects',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Penomoran bab dan subbab dalam dokumen panjang sering kacau karena dibuat manual. Agar struktur dokumen rapi dan mudah diperbarui, sebaiknya menggunakan ...',
                        'a' => 'Manual numbering dengan mengetik 1, 2, 3',
                        'b' => 'Bullets biasa untuk semua judul',
                        'c' => 'Text Box untuk setiap judul',
                        'd' => 'Multilevel List yang terhubung dengan Heading Styles',
                        'jawaban' => 'D'
                    ],
                    [
                        'text' => 'Judul subbab sering muncul sendirian di bagian paling bawah halaman, sementara isinya pindah ke halaman berikutnya. Pengaturan yang tepat untuk mencegah hal ini adalah ...',
                        'a' => 'Shrink One Page',
                        'b' => 'Keep with next pada pengaturan paragraph',
                        'c' => 'Change Case',
                        'd' => 'Insert Symbol',
                        'jawaban' => 'B'
                    ],
                ];
            }

            // SOAL EXCEL
            elseif ($ujian->tipe == 'excel') {

                $soals = [
                    [
                        'text' => 'Alamat sel B3 berarti ...',
                        'a' => 'Kolom B baris 3',
                        'b' => 'Kolom 3 baris B',
                        'c' => 'Sheet B halaman 3',
                        'd' => 'Baris B kolom 3',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Setiap rumus di Microsoft Excel harus diawali dengan tanda ...',
                        'a' => '#',
                        'b' => '@',
                        'c' => '=',
                        'd' => '$',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Fungsi SUM digunakan untuk ...',
                        'a' => 'Menghitung rata-rata',
                        'b' => 'Menjumlahkan data angka',
                        'c' => 'Mencari nilai terbesar',
                        'd' => 'Menghitung jumlah teks',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Fitur AutoFill berguna untuk ...',
                        'a' => 'Menghapus isi sel',
                        'b' => 'Mengunci worksheet',
                        'c' => 'Mencetak grafik',
                        'd' => 'Menyalin pola data atau rumus secara otomatis',
                        'jawaban' => 'D'
                    ],
                    [
                        'text' => 'Untuk menampilkan angka sebagai mata uang, pengaturan yang digunakan adalah ...',
                        'a' => 'Number Format Currency/Accounting',
                        'b' => 'Merge Cells',
                        'c' => 'Page Layout',
                        'd' => 'Text Direction',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Agar baris judul tetap terlihat saat menggulir data ke bawah, fitur yang digunakan adalah ...',
                        'a' => 'Wrap Text',
                        'b' => 'Merge and Center',
                        'c' => 'Freeze Panes',
                        'd' => 'Goal Seek',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Perintah Sort Ascending digunakan untuk ...',
                        'a' => 'Menghapus data ganda',
                        'b' => 'Mengurutkan data dari kecil ke besar atau A ke Z',
                        'c' => 'Menyembunyikan seluruh kolom',
                        'd' => 'Mengubah data menjadi grafik',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Fitur Filter digunakan untuk ...',
                        'a' => 'Menampilkan data tertentu sesuai kriteria',
                        'b' => 'Menghapus seluruh worksheet',
                        'c' => 'Mengubah rumus menjadi teks',
                        'd' => 'Menyisipkan komentar otomatis',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Chart pada Excel berfungsi untuk ...',
                        'a' => 'Mengunci sel',
                        'b' => 'Membuat surat otomatis',
                        'c' => 'Menyajikan data dalam bentuk visual',
                        'd' => 'Mengubah file menjadi PDF',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Cara cepat mengganti nama worksheet adalah ...',
                        'a' => 'Menekan Ctrl+P',
                        'b' => 'Menghapus semua sel',
                        'c' => 'Membuka menu Mailings',
                        'd' => 'Klik dua kali pada tab sheet lalu mengetik nama baru',
                        'jawaban' => 'D'
                    ],
                    [
                        'text' => 'Referensi sel $A$1 pada rumus Excel disebut referensi ...',
                        'a' => 'Relatif',
                        'b' => 'Absolut',
                        'c' => 'Campuran tanpa fungsi',
                        'd' => 'Teks biasa',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Fungsi yang tepat untuk menghitung jumlah data berdasarkan satu kriteria adalah ...',
                        'a' => 'COUNTIF',
                        'b' => 'SUM',
                        'c' => 'MAX',
                        'd' => 'LEFT',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Untuk mencari harga barang berdasarkan kode produk dari tabel referensi, fungsi yang umum digunakan adalah ...',
                        'a' => 'AVERAGE',
                        'b' => 'NOW',
                        'c' => 'VLOOKUP atau XLOOKUP',
                        'd' => 'LEN',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'D'
                    ],
                    [
                        'text' => 'Untuk memberi warna otomatis pada nilai yang berada di bawah KKM, fitur yang tepat adalah ....',
                        'a' => 'Freeze Panes',
                        'b' => 'Conditional Formatting',
                        'c' => 'Remove Duplicates',
                        'd' => 'Text to Columns',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Jika terdapat data siswa yang sama tercatat dua kali, fitur yang tepat untuk membersihkannya adalah ...',
                        'a' => 'Goal Seek',
                        'b' => 'Page Break Preview',
                        'c' => 'Remove Duplicates',
                        'd' => 'Format Painter',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Untuk merangkum penjualan berdasarkan kategori produk dan wilayah secara cepat, fitur yang paling tepat adalah ...',
                        'a' => 'PivotTable',
                        'b' => 'WordArt',
                        'c' => 'Page Border',
                        'd' => 'Screenshot',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Agar rumus tidak sengaja diubah oleh pengguna lain, worksheet dapat diamankan melalui fitur ...',
                        'a' => 'Insert Symbol',
                        'b' => 'Protect Sheet',
                        'c' => 'Sort Descending',
                        'd' => 'Name Manager saja',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Data nama lengkap dalam satu kolom ingin dipisah menjadi nama depan dan nama belakang. Fitur yang sesuai adalah ...',
                        'a' => 'Merge and Center',
                        'b' => 'Freeze Panes',
                        'c' => 'AutoSum',
                        'd' => 'Text to Columns',
                        'jawaban' => 'D'
                    ],
                    [
                        'text' => 'Untuk membuat pilihan input berbentuk daftar seperti "Hadir", "Izin", "Sakit", fitur yang digunakan adalah ...',
                        'a' => 'Flash Fill saja',
                        'b' => 'Subtotal',
                        'c' => 'Data Validation',
                        'd' => 'Page Orientation',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Rumus yang tepat untuk menampilkan "Lulus" jika nilai di A2 minimal 70, selain itu "Tidak Lulus", adalah ...',
                        'a' => '=SUM(A2>=70,"Lulus","Tidak Lulus")',
                        'b' => '=COUNTIF(A2,"Lulus")',
                        'c' => '=AVERAGE(A2,"Lulus")',
                        'd' => '=IF(A2>=70,"Lulus","Tidak Lulus")',
                        'jawaban' => 'C'
                    ],
                    
                ];
            }

            // 🔥 INSERT KE DATABASE
            foreach ($soals as $s) {
                Pertanyaan::create([
                    'ujian_id' => $ujian->id,
                    'text_pertanyaan' => $s['text'],
                    'opsi_a' => $s['a'],
                    'opsi_b' => $s['b'],
                    'opsi_c' => $s['c'],
                    'opsi_d' => $s['d'],
                    'jawaban_benar' => $s['jawaban'],
                    'skor' => 100 / 5
                ]);
            }
        }
    }
}
