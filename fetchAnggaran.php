<?php
include 'koneksi.php';

if (isset($_POST['nama_bagian'], $_POST['bulan'])) {
    $nama_bagian = mysqli_real_escape_string($conn, $_POST['nama_bagian']);
    $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);

    $query = "SELECT total_anggaran FROM anggaran WHERE bagian = '$nama_bagian' AND bulan = '$bulan' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo number_format($row['total_anggaran'], 0, ',', '.');
    } else {
        echo "0";
    }
}
?>
