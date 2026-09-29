<?php
require_once __DIR__ . '/src/App/Models/Mahasiswa.php';

use App\Models\Mahasiswa;

$mhs = new Mahasiswa();
echo $mhs->sapa();