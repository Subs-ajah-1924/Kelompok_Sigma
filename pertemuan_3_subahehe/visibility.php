<?php
Class Contoh
{
    public string $nama = "andi";
    protected string $pass = "x123";
    private string $kunci = "SANGAT RAHASIA";

    public function lihatKunci(): string 
    {
        return $this->kunci;
    }
}

$contoh = new Contoh();

echo $contoh->nama . "<br>";
// echo $contoh->pass;
// echo $contoh->kunci;
echo $contoh->lihatKunci();