<?php

function hitungIpk(array $nilai): float {
    return array_sum($nilai) / count($nilai);
}

function sapa(string $nama){
    echo "halo " . $nama . "<br>";
}

function buatKartu(int $nim, int $tahun, $status ="mahasiswa"){
    echo "Nim : " . $nim . "<br>";
    echo "Tahun : " . $tahun . "<br>";
    echo "status : " . $status . "<br>";
}

echo hitungIpk([2, 4, 3.5]) . "<br>";
sapa("subahehe ganteng banget");
buatKartu(241240001523,2022,"gembel");