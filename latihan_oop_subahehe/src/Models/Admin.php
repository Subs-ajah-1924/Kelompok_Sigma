<?php

namespace App\Models;

class Admin extends Pengguna
{
    public function hapusData(string $table): void 
    {
        echo "Data Tabel '{$table}' telah di hapus oleh admin\n";
    }
}