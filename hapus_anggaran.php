<?php
include 'koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('ID tidak valid!'); window.location.href='data_anggaran.php';</script>";
    exit();
}

$sql = "DELETE FROM anggaran WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Data berhasil dihapus!'); window.location.href='data_anggaran.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data!');</script>";
}
?>
