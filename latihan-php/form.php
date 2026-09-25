<?php
$nama = isset($_GET['nama']) ? $_GET['nama'] : '-';
?>
<form method="GET" action="form.php">
    <input type="text" name="nama" required>
    <button>Kirim</button>
</form>
<p>Halo, <strong><?= htmlspecialchars($nama) ?></strong>!</p>