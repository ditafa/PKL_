<?php
include 'koneksi.php'; // Pastikan ada koneksi database

// Query untuk mengambil data dari tabel kesejahteraan, angkas, dan realisasi
$sql = "SELECT k.*, a.total_anggaran, r.realisasi_gu, r.realisasi_ls, a.bulan,
               (a.total_anggaran - (r.realisasi_gu + r.realisasi_ls)) AS sisa_anggaran,
               a.keterangan_bukti  -- Ambil keterangan bukti dari tabel angkas
        FROM kesejahteraan k
        JOIN angkas a ON k.id = a.id_kesejahteraan
        LEFT JOIN realisasi r ON k.id = r.id_kesejahteraan"; // Menggunakan LEFT JOIN untuk menghindari kehilangan data kesejahteraan

$result = mysqli_query($conn, $sql);

// Periksa apakah query berhasil
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tersimpan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Data Tersimpan Kesejahteraan Rakyat</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Program</th>
                    <th>Kegiatan</th>
                    <th>Sub Kegiatan</th>
                    <th>Kode Sub Kegiatan</th> <!-- Tambahkan kolom Kode Sub Kegiatan -->
                    <th>Kode Rekening</th>
                    <th>Uraian</th>
                    <th>Total Anggaran</th>
                    <th>Realisasi GU</th>
                    <th>Realisasi LS</th>
                    <th>Sisa Anggaran</th>
                    <th>Bulan</th> <!-- Tambahkan kolom Bulan -->
                    <th>Keterangan Bukti</th> <!-- Tambahkan kolom Keterangan Bukti -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nama_program']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_kegiatan']); ?></td>
                    <td><?php echo htmlspecialchars($row['sub_kegiatan']); ?></td> <!-- Menampilkan sub kegiatan -->
                    <td><?php echo htmlspecialchars($row['kode_sub_kegiatan']); ?></td> <!-- Menampilkan kode sub kegiatan -->
                    <td><?php echo htmlspecialchars($row['kd_rek']); ?></td>
                    <td><?php echo htmlspecialchars($row['uraian']); ?></td>
                    <td><?php echo number_format($row['total_anggaran'], 0, ',', '.'); ?></td>
                    <td><?php echo number_format($row['realisasi_gu'], 0, ',', '.'); ?></td>
                    <td><?php echo number_format($row['realisasi_ls'], 0, ',', '.'); ?></td>
                    <td><?php echo number_format($row['sisa_anggaran'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($row['bulan']); ?></td> <!-- Tampilkan bulan -->
                    <td><?php echo htmlspecialchars($row['keterangan_bukti']); ?></td> <!-- Tampilkan keterangan bukti -->
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>