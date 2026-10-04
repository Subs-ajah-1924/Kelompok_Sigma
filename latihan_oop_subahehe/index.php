<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Pengguna;
use App\Models\Admin;

echo " DEMO OOP + AUTOLOAD";

$mhs = new Mahasiswa("Andi", 2.0);
echo $mhs->sapa() . "\n";
echo "Status : " . $mhs->statusLulus() . "\n\n";

$admin = new Admin(
    "Subahehe",
    "Subahehebruhhhhhh@gmail.com",
    password_hash("ini rahasia sekali", PASSWORD_DEFAULT)
);

$cek = $admin->login("ini rahasia");
echo "[LOGIN]" . $admin->nama() . "=>" . ($cek ? "berhasil" : "gagal") . "\n";
$admin->hapusData("mahasiswa");
echo "\n";

$dosen = new Dosen(
    "Bapak Joko",
    "joko@unsia.ac.id",
    password_hash("sandi-aman", PASSWORD_DEFAULT),
    ["Pemrograman Berbasis Platform", "Rekayasa Perangkat Lunak"],
);
echo $dosen->tampilkanMatKul() . "\n";