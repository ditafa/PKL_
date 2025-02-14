<?php
include 'koneksi.php'; // Pastikan ada koneksi database

$sql = "SELECT a.*, k.nama_program, k.nama_kegiatan FROM angkas a
        JOIN kesejahteraan k ON a.id_kesejahteraan = k.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Anggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Data Anggaran</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Program</th>
                    <th>Kegiatan</th>
                    <th>Total Anggaran</th>
                    <th>Bulan</th>
                    <th>Sisa Anggaran</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['nama_program']; ?></td>
                    <td><?php echo $row['nama_kegiatan']; ?></td>
                    <td><?php echo number_format($row['total_anggaran'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['bulan']; ?></td>
                    <td><?php echo number_format($row['sisa_anggaran'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>