<?php
include 'koneksi.php';

// Tambah Data Anggaran Kas dengan Validasi Total
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $bagian = mysqli_real_escape_string($conn, $_POST['bagian']);
    $total = intval($_POST['total_anggaran']);
    $bulan = [];
    
    for ($i = 1; $i <= 12; $i++) {
        $bulan[$i] = intval($_POST["bulan$i"]);
    }

    // Validasi Total Anggaran Bulanan = Total Anggaran
    $total_bulan = array_sum($bulan);
    if ($total_bulan !== $total) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Jumlah total anggaran bulanan tidak sesuai!',
                text: 'Total anggaran bulanan harus sama dengan Total Anggaran.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.history.back();
            });
        </script>";
        exit;
    }

    // Simpan ke database
    $sql = "INSERT INTO anggaran (bagian, total_anggaran, januari, februari, maret, april, mei, juni, juli, agustus, september, oktober, november, desember) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiiiiiiiiiiii", $bagian, $total, $bulan[1], $bulan[2], $bulan[3], $bulan[4], $bulan[5], $bulan[6], 
                      $bulan[7], $bulan[8], $bulan[9], $bulan[10], $bulan[11], $bulan[12]);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Data berhasil disimpan!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = 'index.php?page=input_angkas';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal menyimpan data!',
                text: '" . $stmt->error . "',
                showConfirmButton: true
            });
        </script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Anggaran Kas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Input Anggaran Kas</h2>
    
    <form method="post" onsubmit="return cekTotalAnggaran()">
        <div class="mb-3">
            <label for="bagian" class="form-label">Pilih Bagian</label>
            <select id="bagian" class="form-control" name="bagian" required>
                <option value="">-- Pilih Bagian --</option>
                <option value="kesejahteraan">Bagian Kesejahteraan Rakyat</option>
                <option value="perencanaan">Bagian Perencanaan Dan Keuangan</option>
                <option value="organisasi">Bagian Organisasi</option>
                <option value="hukum">Bagian Hukum</option>
                <option value="umum">Bagian Umum Dan Protokol</option>
                <option value="tata_pemerintah">Bagian Tata Pemerintah</option>
                <option value="perekonomian">Bagian Perekonomian, Pembangunan, Dan Sumber Daya</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Total Anggaran Kas</label>
            <input type="number" class="form-control" name="total_anggaran" id="total_anggaran" required>
        </div>

        <?php 
        $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        foreach ($bulan as $key => $namaBulan) { 
        ?>
        <div class="mb-3">
            <label class="form-label">Anggaran <?= $namaBulan ?></label>
            <input type="number" class="form-control bulan-input" name="bulan<?= $key + 1 ?>" required oninput="hitungTotal()">
        </div>
        <?php } ?>

        <div class="alert alert-warning d-none" id="peringatan"></div>
        <button type="submit" name="submit" class="btn btn-success">Simpan</button>
    </form>

    <a href="data_angkas.php" class="btn btn-primary mt-3" id="lihatData">Lihat Data Anggaran</a>
</div>

<script>
    function hitungTotal() {
        let totalAnggaran = parseFloat(document.getElementById("total_anggaran").value) || 0;
        let totalBulan = 0;

        document.querySelectorAll(".bulan-input").forEach(input => {
            totalBulan += parseFloat(input.value) || 0;
        });

        let peringatan = document.getElementById("peringatan");
        if (totalBulan !== totalAnggaran) {
            peringatan.classList.remove("d-none");
            peringatan.textContent = "Total anggaran bulanan harus sama dengan Total Anggaran!";
            document.querySelector("button[name='submit']").disabled = true;
        } else {
            peringatan.classList.add("d-none");
            document.querySelector("button[name='submit']").disabled = false;
        }
    }

    function cekTotalAnggaran() {
        return document.getElementById("peringatan").classList.contains("d-none");
    }

    document.getElementById("lihatData").addEventListener("click", function (event) {
        event.preventDefault();
        Swal.fire({
            icon: 'info',
            title: 'Mengalihkan ke data anggaran...',
            showConfirmButton: false,
            timer: 1200
        }).then(() => {
            window.location.href = "data_angkas.php";
        });
    });
</script>

</body>
</html>
