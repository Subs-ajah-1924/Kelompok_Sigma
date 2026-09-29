<?php
class Mahasiswa
{
    // --- property (data object) ---
    public string $nama;
    public float $ipk;
    // --- constructor: dipanggil otomatis saat new ---
    public function __construct(string $nama, float $ipk)
    {
        $this->nama = $nama;
        $this->ipk = $ipk;
    }
    // --- method (perilaku object) ---
    public function statusKelulusan(): string
    {
        return $this->ipk >= 3.0 ? "Cumlaude" : "Reguler";
    }
}
// --- penggunaan (di luar class) ---
$mhs1 = new Mahasiswa("Andi", 3.6);
$mhs2 = new Mahasiswa("Budi", 2.9);
echo $mhs1->nama . " => " . $mhs1->statusKelulusan() . "\n";
echo $mhs2->nama . " => " . $mhs2->statusKelulusan() . "\n";