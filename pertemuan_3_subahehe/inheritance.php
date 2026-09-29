<?php

class Pengguna
{
    public function __construct(
        protected string $nama,
        protected string $email,
        protected string $passwordHash,
    ){}

    public function login(string $password) : bool {
        return password_verify($password, $this->passwordHash);
    }

    public function nama(): string {
        return $this->nama;
    }
}

class Admin extends Pengguna
{
    public function hapusData(string $tabel) : void
    {
        echo "Data tabel '{$tabel}' dihapus oleh admin.\n";
    }
}

$admin = new Admin("Subahehe","subahehe@gmail.com",password_hash("rahasia_123",PASSWORD_DEFAULT));

echo $admin->nama(). "\n";
// echo $admin->email;
$admin->hapusData("mahasiswa");
