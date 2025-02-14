<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Anggaran Kas</title>
    <link rel="stylesheet" href="style.css"> <!-- Menghubungkan ke style.css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2 class="text-center">Data Anggaran Kas</h2>
    
    <div class="table-container">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Bagian</th>
                    <th>Total Anggaran</th>
                    <th>Januari</th>
                    <th>Februari</th>
                    <th>Maret</th>
                    <th>April</th>
                    <th>Mei</th>
                    <th>Juni</th>
                    <th>Juli</th>
                    <th>Agustus</th>
                    <th>September</th>
                    <th>Oktober</th>
                    <th>November</th>
                    <th>Desember</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'koneksi.php';
                $sql = "SELECT * FROM anggaran";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['bagian']}</td>
                            <td>{$row['total_anggaran']}</td>
                            <td>{$row['januari']}</td>
                            <td>{$row['februari']}</td>
                            <td>{$row['maret']}</td>
                            <td>{$row['april']}</td>
                            <td>{$row['mei']}</td>
                            <td>{$row['juni']}</td>
                            <td>{$row['juli']}</td>
                            <td>{$row['agustus']}</td>
                            <td>{$row['september']}</td>
                            <td>{$row['oktober']}</td>
                            <td>{$row['november']}</td>
                            <td>{$row['desember']}</td>
                            <td>
                                <a href='edit_anggaran.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='hapus_anggaran.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>Hapus</a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
