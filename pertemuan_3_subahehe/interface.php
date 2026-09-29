<?php

interface BolehLogin
{
    public function login(string $password): bool;
}

// Wajib ditulis isinya, bebas caranya
class Mahasiswa implements BolehLogin
{
    public function login(string $password): bool
    {
        // versi sederhana: bandingkan teks
        return $password === "rahasia";
    }
}

class Dosen implements BolehLogin
{
    public function login(string $password): bool
    {
        return ($password === "dosen123" || $password === "admin999");
    }
}

// Bukti gunanya: satu fungsi bisa menerima SEMUA yang implements BolehLogin
function cekMasuk(BolehLogin $subjek, string $pass): string
{
    return $subjek->login($pass) ? "Silakan masuk" : "Akses ditolak";
}

echo cekMasuk(new Mahasiswa(), "rahasia") . "\n";
// Silakan masuk

echo cekMasuk(new Dosen(), "salah") . "\n";
// Akses ditolak
