<?php
$nilai = 85;
if ($nilai >= 80) {
    echo "Grade A";
} elseif ($nilai >= 70) {
    echo "Grade B";
} else {
    echo "Grade C";
}

// match expression (PHP 8) — ringkas, pengganti switch-case:
$predikat = match (true) {
    $nilai >= 80 => "Sangat Baik",
    $nilai >= 70 => "Baik",
    default      => "Perlu Perbaikan",
};

for ($i = 1; $i <= 3; $i++) {
    echo "for ke-$i<br>";
}
$j = 1;
while ($j <= 3) {
    echo "while ke-$j<br>";
    $j++;
}
