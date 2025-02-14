<?php
include 'koneksi.php';

// Pastikan ID ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID tidak ditemukan!");
}

$id = intval($_GET['id']);

// Ambil data dari database berdasarkan ID
$sql = "SELECT * FROM anggaran WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Jika data tidak ditemukan
if (!$data) {
    die("Data tidak ditemukan!");
}

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $bagian = $_POST['bagian'];
    $total_anggaran = intval($_POST['total_anggaran']);
    $bulan = [];

    for ($i = 1; $i <= 12; $i++) {
        $bulan[$i] = intval($_POST["bulan$i"]);
    }

    // Validasi: jumlah total bulanan harus sama dengan total anggaran
    $total_bulan = array_sum($bulan);
    if ($total_bulan !== $total_anggaran) {
        echo "<script>alert('Jumlah total anggaran bulanan harus sama dengan Total Anggaran!'); window.history.back();</script>";
        exit;
    }

    // Update data di database
    $sql = "UPDATE anggaran SET bagian = ?, total_anggaran = ?, 
            januari = ?, februari = ?, maret = ?, april = ?, mei = ?, juni = ?, 
            juli = ?, agustus = ?, september = ?, oktober = ?, november = ?, desember = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiiiiiiiiiiiii", $bagian, $total_anggaran, 
                      $bulan[1], $bulan[2], $bulan[3], $bulan[4], $bulan[5], $bulan[6], 
                      $bulan[7], $bulan[8], $bulan[9], $bulan[10], $bulan[11], $bulan[12], $id);

    if ($stmt->execute()) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    let notif = document.createElement('div');
                    notif.innerHTML = 'Data berhasil diperbarui!';
                    notif.style.cssText = 'position:fixed; top:10px; right:10px; background:green; color:white; padding:10px; border-radius:5px;';
                    document.body.appendChild(notif);
                    setTimeout(() => notif.remove(), 7000);
                    setTimeout(() => window.location.href='data_angkas.php', 2000);
                });
              </script>";
    } else {
        echo "<script>alert('Gagal mengupdate data: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anggaran Kas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Edit Anggaran Kas</h2>

    <form method="post">
        <div class="mb-3">
            <label for="bagian" class="form-label">Pilih Bagian</label>
            <select id="bagian" class="form-control" name="bagian" required>
                <option value="kesejahteraan" <?= ($data['bagian'] == 'kesejahteraan') ? 'selected' : '' ?>>Bagian Kesejahteraan Rakyat</option>
                <option value="perencanaan" <?= ($data['bagian'] == 'perencanaan') ? 'selected' : '' ?>>Bagian Perencanaan Dan Keuangan</option>
                <option value="organisasi" <?= ($data['bagian'] == 'organisasi') ? 'selected' : '' ?>>Bagian Organisasi</option>
                <option value="hukum" <?= ($data['bagian'] == 'hukum') ? 'selected' : '' ?>>Bagian Hukum</option>
                <option value="umum" <?= ($data['bagian'] == 'umum') ? 'selected' : '' ?>>Bagian Umum Dan Protokol</option>
                <option value="tata_pemerintah" <?= ($data['bagian'] == 'tata_pemerintah') ? 'selected' : '' ?>>Bagian Tata Pemerintah</option>
                <option value="perekonomian" <?= ($data['bagian'] == 'perekonomian') ? 'selected' : '' ?>>Bagian Perekonomian, Pembangunan, Dan Sumber Daya</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Total Anggaran Kas</label>
            <input type="number" class="form-control" name="total_anggaran" id="total_anggaran" required value="<?= $data['total_anggaran'] ?>">
        </div>

        <?php 
        $bulan_nama = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        for ($i = 1; $i <= 12; $i++) { 
            $bulan_field = strtolower($bulan_nama[$i - 1]); // Sesuai nama kolom di database
        ?>
        <div class="mb-3">
            <label class="form-label">Anggaran <?= $bulan_nama[$i - 1] ?></label>
            <input type="number" class="form-control bulan-input" name="bulan<?= $i ?>" required value="<?= $data[$bulan_field] ?>" oninput="hitungTotal()">
        </div>
        <?php } ?>

        <div class="alert alert-warning d-none" id="peringatan"></div>
        <button type="submit" name="update" class="btn btn-success">Update</button>
    </form>
</div>

<script>
    function hitungTotal() {
        let totalAnggaran = parseFloat(document.getElementById("total_anggaran").value) || 0;
        let totalBulan = 0;

        document.querySelectorAll(".bulan-input").forEach(input => {
            totalBulan += parseFloat(input.value) || 0;
        });

        let peringatan = document.getElementById("peringatan");
        if (totalBulan < totalAnggaran) {
            peringatan.classList.remove("d-none");
            peringatan.textContent = "Total anggaran bulanan masih kurang!";
        } else if (totalBulan > totalAnggaran) {
            peringatan.classList.remove("d-none");
            peringatan.textContent = "Total anggaran bulanan melebihi batas!";
        } else {
            peringatan.classList.add("d-none");
        }
    }
</script>

</body>
</html>
