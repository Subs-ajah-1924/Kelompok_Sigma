<?php 

namespace App\Models;

use App\Contracts\BolehLogin;

class Dosen extends Pengguna implements Bolehlogin
{
    public function __construct(
        string $nama,
        string $email,
        string $passswordHash,
        public array $mataKuliah = [],
    ){
        parent::__construct($nama, $email, $passswordHash);
    }

    public function tampilkanMatKul(): string
    {
        return  $this->nama . " mengampu:" . implode(", ", $this->mataKuliah);
    }
}