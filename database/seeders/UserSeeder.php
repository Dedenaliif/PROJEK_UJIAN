<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // akun default
        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'penguji',
            'password' => bcrypt('penguji'),
            'role' => 'penguji',
        ]);

        User::create([
            'username' => 'siswa',
            'password' => bcrypt('siswa'),
            'role' => 'siswa',
        ]);

        User::create([
            'username' => 'pengawas',
            'password' => bcrypt('pengawas'),
            'role' => 'pengawas',
        ]);

        // $data = [

        //     'ABI MANYU ZULFIKAR',
        //     'ADEN RANGGA DARMAWAN',
        //     'AGUNG SULISTIO',
        //     'AHMAD FARID KAMALUDIN',
        //     'AMALINA KHOIRRU UMMAH',
        //     'ANTON RAIZRAMA SATRIO',
        //     'ARGA MUKTI',
        //     'AZHAR DIPRAJA',
        //     'BAGAS PRASETYO',
        //     'BANYU BUMI FIRDAUS',
        //     'DESTA MUHAMMAD FAJAR',
        //     'DIRLI NAJAR RABI GALIH',
        //     'EGAR ARTHA PRIATNA',
        //     'ERLANGGA MAULANA FIRDAUS',
        //     'FITRIA JAYA LESTARI',
        //     'GILANG PERDANA KHENSI',
        //     'HABI HUSNI MUBAROK',
        //     'HERLINA',
        //     'ILHAM RAMADHAN SAEPUDIN',
        //     'M. ANDHIKA PRADITIYA SETIAWAN',
        //     'MARSEL PUTRA MUHAJIRIN',
        //     'MOCHAMAD LUTHFI KHAIRULKAMIL NURRAFIQ',
        //     'MUHAMAD DAVA PRATAMA',
        //     'MUHAMAD HAFIDZ HAMDANIE',
        //     'MUHAMAD RAMADANI NUGRAHA',
        //     'MUHAMMAD AKMAL PRAWARA TSUKI',
        //     'MUHAMMAD RIZKAR RAFSANZANI',
        //     'MUHAMMAD VITORIO GERONIMO',
        //     'RAFFLY ALMIYADI',
        //     'RAKHA HAIRUL FAJRI',
        //     'RAMANDANI KURNIA PUTRA',
        //     'RIDWAN PATUR RAHMAN',
        //     'RIZAL RAMDANI',
        //     'RIZKI CAHYADI',
        //     'RIZKY HIDAYAH FAUZIE',
        //     'SEPTI NUGRAHA PUTRA',
        //     'SITI MUDRIKAH',
        //     'SYAFRIZAL BINUKO ARGA RADITYA NARDIANTA',
        //     'VICKY VENDI SAEPUDIN',
        //     'WAHYU SETIYAWAN',

        //     'AURELLIA AZZAHRA PUTRI',
        //     'DEA NISHA ARYANI',
        //     'DHEAMI KHOIRUNISA',
        //     'FAUZAN WIRYA PRATAMA',
        //     'GALUH GIBRANI PRADANA',
        //     'GISELLA DEWI APRILIA',
        //     'INDRIYANI',
        //     'KAILANI TIARA IVANA',
        //     'KEISHA AMELIA BAHARIZKI',
        //     'LUNA SUCI NUR WIBOWO',
        //     'LUTFI SYAHFFANA AZZAHRA',
        //     'MILA MARYANI',
        //     'MUHAMAD ZACKY MAULANA',
        //     'NABILA RASYIFA ZAHRA',
        //     'NOLY NAUZI RAMADHAN',
        //     'NURMALA',
        //     'PUTRI NURRIFDA',
        //     'RAHMA AULIA RAMADANI',
        //     'RD. YUDI DARMAWAN',
        //     'REZA RAHMAN JULIADI',
        //     'SANDRIYA',
        //     'SHERA DWI RAHMAWATI',
        //     'SHEVTIAN ARDIANSYAH',
        //     'SHIFA RUPIANTI PUTRI',
        //     'SYIFA FITRI RAMADHANI',
        //     'YURIKE RAMADHANY',
        //     'ZAHRA PUTRI AULIA',

        //     'AHMAD FAUZAN SEPTIANA',
        //     'AHMAD YAYANG PRIADI',
        //     'ALFI AR RASYID',
        //     'ALIYCIA SAYIDINA JULIASARI YUSUF',
        //     'ANISA ANGGUN ROMADONA',
        //     'AURA ZASKIA RAMADHANI',
        //     'DERI JUANDA',
        //     'DEWI ARINI',
        //     'DIMAS NUGRAHA',
        //     'DZAKIYAN DZIKRAN KHAERUL',
        //     'DZAKY SATYA PERKASA',
        //     'FABYAN RAFFI SETIAWAN',
        //     'HESTI FEBRIYANI',
        //     'IIS ROSDIANA',
        //     'ILHAM MAULANA HARYADI',
        //     'KAILA ANASTASIA',
        //     'KEYZIA ZHAVIRA CHALLISHA',
        //     'MALEA ANATASIA',
        //     'MEYLANY ISSYA VADILLAH',
        //     'MEYSYA NOER INDRIANI',
        //     'MOCH. NOER ARIRAFI FABER',
        //     'MOCHAMAD FERDIAN GAUTAMA PURWANDI',
        //     'MOHAMAD RUSLIN MUTTAQIN',
        //     'MUHAMMAD NUR HAFIZ',
        //     'MUHAMMAD RAYHAN',
        //     'MUHAMMAD RIZKY SAUQI AKBAR',
        //     'NABILA RAHMA FITRIA',
        //     'NANDA RIZKY FAUJIAH',
        //     'NAUFAL FAUZAN',
        //     'NAYA YONIRA',
        //     'NAZWA SHYAIRA RHATASYA',
        //     'NEYLA PERMATA KUSUMAWATI',
        //     'OKTAVIA RAMADANI',
        //     'RAYSHA RAMADHANI',
        //     'RISA NUR ANDIYANI',
        //     'RISKA AULIA',
        //     'SALWA SOFIAH LESTARI',
        //     'SILVI NABILA',
        //     'SRI ANISA',
        //     'TEUKU MUHAMMAD RAFI',
        //     'YUSI NUR MEILINDA',

        //     'AGUNG NUGRAHA',
        //     'ALVARO APRILLIAN',
        //     'ANASTASYA FELISHA SIAHAYA LOPULIZA',
        //     'ARIEF RISKI MAULANA',
        //     'ARKANSYAH',
        //     'ARSEL AGNI JUL FIRLI',
        //     'BAYU SURYA RAHMAN',
        //     'DAVI SEPTIAN GUMELAR',
        //     'DIAN FITRIANI',
        //     'DRAJAD RAMDHAN WIDI PUTRA',
        //     'EZAR RADITYA IRAWAN',
        //     'IKHSAN FAIZ HILMI',
        //     'JANUAR MAULANA FADLI',
        //     'MOCHAMAD ARDI DARMANSAH',
        //     'MUHAMAD IFAN SETIAWAN',
        //     'MUHAMAD RIZKY',
        //     'MUHAMAD YANUAR AL MUQTADAR',
        //     'MUHAMAD ZULFA ABDILLAH',
        //     'MUHAMMAD FAUZAN FATHURAHMAN',
        //     'NABILA RISKI SUCIANTI',
        //     'RAKA DWI ERYAN',
        //     'RINGGA DESWA FIREL DIARKA',
        //     'RIZKI REVALDY',
        //     'ROBBY SAPUTRA',
        //     'SITI RAISYA',
        //     'SITI ROHMAH',
        //     'WAHYU HIDAYAT',
        //     'YUSNANDINI NURUL HIKMAH',
        // ];

        // foreach ($data as $nama) {

        //     if ($nama === 'SITI RAISYA') {
        //         $username = 'sitiraisya';

        //     } elseif ($nama === 'SITI ROHMAH') {
        //         $username = 'sitirohmah';

        //     } else {

        //         $parts = preg_split('/\s+/', strtolower(trim($nama)));

        //         if (count($parts) === 1) {
        //             $username = $parts[0];
        //         } else {
        //             $inisial = '';

        //             for ($i = 1; $i < count($parts); $i++) {
        //                 $inisial .= substr($parts[$i], 0, 1);
        //             }

        //             $username = $parts[0] . '.' . $inisial;
        //         }
        //     }

        //     User::create([
        //         'username' => $username,
        //         'password' => bcrypt($username),
        //         'role' => 'siswa',
        //     ]);
        // }
    }
}
