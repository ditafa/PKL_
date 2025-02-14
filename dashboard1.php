<?php
include 'koneksi.php'; // Pastikan ada koneksi database

// Ambil semua data dari tabel kesejahteraan untuk ditampilkan
$sql_kesejahteraan = "SELECT * FROM kesejahteraan";
$result_kesejahteraan = mysqli_query($conn, $sql_kesejahteraan);

// Ambil data anggaran berdasarkan bagian dan bulan
$total_anggaran = 0; // Inisialisasi total anggaran
if (isset($_POST['bagian'], $_POST['bulan'])) {
    $bagian = mysqli_real_escape_string($conn, $_POST['bagian']);
    $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);
    
    $sql_anggaran = "SELECT total_anggaran FROM angkas WHERE bagian = ? AND bulan = ?";
    $stmt_anggaran = mysqli_prepare($conn, $sql_anggaran);
    mysqli_stmt_bind_param($stmt_anggaran, "ss", $bagian, $bulan);
    mysqli_stmt_execute($stmt_anggaran);
    mysqli_stmt_bind_result($stmt_anggaran, $total_anggaran);
    mysqli_stmt_fetch($stmt_anggaran);
    mysqli_stmt_close($stmt_anggaran);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan semua input ada sebelum mengaksesnya
    if (isset($_POST['nama_program'], $_POST['nama_kegiatan'], $_POST['kode_sub_kegiatan'], $_POST['kd_rek'], $_POST['uraian'], $_POST['total_anggaran'], $_POST['bulan'], $_POST['sub_kegiatan'], $_POST['keterangan_bukti'])) {
        $nama_program = mysqli_real_escape_string($conn, $_POST['nama_program']);
        $nama_kegiatan = mysqli_real_escape_string($conn, $_POST['nama_kegiatan']);
        $sub_kegiatan = mysqli_real_escape_string($conn, $_POST['sub_kegiatan']);
        $kd_rek = mysqli_real_escape_string($conn, $_POST['kd_rek']);
        $uraian = mysqli_real_escape_string($conn, $_POST['uraian']);
        $total_anggaran = intval($_POST['total_anggaran']);
        $jumlah_realisasi = intval($_POST['jumlah_realisasi']);
        $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);
        $bukti = mysqli_real_escape_string($conn, $_POST['bukti']);
        $keterangan_bukti = mysqli_real_escape_string($conn, $_POST['keterangan_bukti']);

        // Hitung sisa anggaran
        $sisa_anggaran = $total_anggaran - $jumlah_realisasi;

        // Simpan data ke tabel kesejahteraan
        $sql_kesejahteraan = "INSERT INTO kesejahteraan (nama_program, nama_kegiatan, sub_kegiatan, kd_rek, uraian, total_anggaran, bulan, bukti, keterangan_bukti)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_kesejahteraan = mysqli_prepare($conn, $sql_kesejahteraan);
        mysqli_stmt_bind_param($stmt_kesejahteraan, "sssssssss", $nama_program, $nama_kegiatan, $sub_kegiatan, $kd_rek, $uraian, $total_anggaran, $bulan, $bukti, $keterangan_bukti);

        if (mysqli_stmt_execute($stmt_kesejahteraan)) {
            // Ambil ID terakhir yang disimpan
            $id_kesejahteraan = mysqli_insert_id($conn);

            // Simpan data anggaran ke tabel angkas
            $sql_angkas = "INSERT INTO angkas (id_kesejahteraan, total_anggaran, bulan, sisa_anggaran, keterangan_bukti) VALUES (?, ?, ?, ?, ?)";
            $stmt_angkas = mysqli_prepare($conn, $sql_angkas);
            mysqli_stmt_bind_param($stmt_angkas, "iissi", $id_kesejahteraan, $total_anggaran, $bulan, $sisa_anggaran, $keterangan_bukti);
            
            mysqli_stmt_execute($stmt_angkas);
            mysqli_stmt_close($stmt_angkas);

            echo "<script>alert('Data berhasil disimpan!');</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt_kesejahteraan);
    } else {
        echo "Error: Form tidak lengkap.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Kesejahteraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Fungsi untuk mengupdate sub kegiatan berdasarkan kode sub kegiatan yang dipilih
        function updateSubKegiatan() {
            var kodeSubKegiatan = document.getElementById("kode_sub_kegiatan").value;
            var subKegiatanSelect = document.getElementById("sub_kegiatan");
            
            // Clear existing options
            subKegiatanSelect.innerHTML = "";

            // Define sub kegiatan berdasarkan kode sub kegiatan
            var subKegiatanOptions = {
                "4.01.02.2.02.0001": [
                    "Fasilitas Pengelolaan Bina Mental Spiritual"
                ],
                "4.01.02.2.02.0002": [
                    "Pelaksanaan Kebijakan, Evaluasi, dan Pencapaian Kinerja Terkait Kesejahteraan Sosial"
                ],
                "4.01.02.2.02.0003": [
                    "Pelaksanaan Kebijakan, Evaluasi, dan Pencapaian Kinerja Terkait Kesejahteraan Masyarakat"
                ]
            };

            // Get the selected sub kegiatan options
            var options = subKegiatanOptions[kodeSubKegiatan] || [];
            options.forEach(function(subKegiatan) {
                var option = document.createElement("option");
                option.value = subKegiatan;
                option.textContent = subKegiatan;
                subKegiatanSelect.appendChild(option);
            });
        }

        // Fungsi untuk mengupdate uraian berdasarkan kode rekening yang dipilih
        function updateUraian() {
            var kodeRekening = document.getElementById("kd_rek").value;
            var uraianSelect = document.getElementById("uraian");
            
            // Clear existing options
            uraianSelect.innerHTML = "";

            // Define uraian berdasarkan kode rekening
            var uraianOptions = {
                "5.1.02.01.01.0002": [
                    "Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Cetak",
                    "Belanja Alat/Bahan untuk Kegiatan Kantor-Kertas dan Cover"
                ],
                "5.1.02.01.01.0004": [
                    "Belanja Alat/Bahan untuk Kegiatan Kantor-Suvebir/Cendera Mata",
                    "Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor"
                ],
                "5.1.02.01.01.0024": [
                    "Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Komputer"
                ],
                "5.1.02.01.01.0025": [
                    "Belanja Alat/Bahan untuk Kegiatan Kantor-Lainnya"
                ],
                "5.1.02.01.01.0026": [
                    "Belanja Alat/Bahan untuk Kegiatan Kantor-Contoh Uraian"
                ]
            };

            // Get the selected uraian options
            var options = uraianOptions[kodeRekening] || [];
            options.forEach(function(uraian) {
                var option = document.createElement("option");
                option.value = uraian;
                option.textContent = uraian;
                uraianSelect.appendChild(option);
            });
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Input Data Kesejahteraan Rakyat</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Program</label>
                <select name="nama_program" class="form-control" required>
                    <option value="">Pilih Program</option>
                    <option value="Program PEMERINTAHAN DAN KESEJAHTERAAN RAKYAT">Program PEMERINTAHAN DAN KESEJAHTERAAN RAKYAT</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kegiatan</label>
                <select name="nama_kegiatan" class="form-control" required>
                    <option value="">Pilih Kegiatan</option>
                    <option value="Pelaksanaan Kebijakan Kesejahteraan Rakyat">Pelaksanaan Kebijakan Kesejahteraan Rakyat</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kode Sub Kegiatan</label>
                <select id="kode_sub_kegiatan" name="kode_sub_kegiatan" class="form-control" required onchange="updateSubKegiatan()">
                    <option value="">Pilih Sub Kegiatan</option>
                    <option value="4.01.02.2.02.0001">4.01.02.2.02.0001</option>
                    <option value="4.01.02.2.02.0002">4.01.02.2.02.0002</option>
                    <option value="4.01.02.2.02.0003">4.01.02.2.02.0003</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Sub Kegiatan</label>
                <select id="sub_kegiatan" name="sub_kegiatan" class="form-control" required>
                    <option value="">Pilih Sub Kegiatan</option>
                    <!-- Options will be populated based on the selected Kode Sub Kegiatan -->
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kode Rekening</label>
                <select id="kd_rek" name="kd_rek" class="form-control" required onchange="updateUraian()">
                    <option value="">Pilih Kode Rekening</option>
                    <option value="5.1.02.01.01.0002">5.1.02.01.01.0002</option>
                    <option value="5.1.02.01.01.0004">5.1.02.01.01.0004</option>
                    <option value="5.1.02.01.01.0024">5.1.02.01.01.0024</option>
                    <option value="5.1.02.01.01.0025">5.1.02.01.01.0025</option>
                    <option value="5.1.02.01.01.0026">5.1.02.01.01.0026</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Uraian</label>
                <select id="uraian" name="uraian" class="form-control" required>
                    <option value="">Uraian Kegiatan</option>
                    <!-- Options will be populated based on the selected Kode Rekening -->
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Total Anggaran (Rp)</label>
                <input type="number" name="total_anggaran" class="form-control" value="<?php echo $total_anggaran; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jumlah Realisasi (Rp)</label>
                <input type="number" name="jumlah_realisasi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select" required>
                    <option value="">-- Pilih Bulan --</option>
                    <option value="Januari">Januari</option>
                    <option value="Februari">Februari</option>
                    <option value="Maret">Maret</option>
                    <option value="April">April</option>
                    <option value="Mei">Mei</option>
                    <option value="Juni">Juni</option>
                    <option value="Juli">Juli</option>
                    <option value="Agustus">Agustus</option>
                    <option value="September">September</option>
                    <option value="Oktober">Oktober</option>
                    <option value="November">November</option>
                    <option value="Desember">Desember</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Bukti</label>
                <input type="text" name="bukti" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Keterangan Bukti</label>
                <textarea name="keterangan_bukti" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>

        <h3 class="mt-5">Data yang Tersimpan</h3>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Program</th>
                    <th>Kegiatan</th>
                    <th>Sub Kegiatan</th>
                    <th>Kode Rekening</th>
                    <th>Uraian</th>
                    <th>Total Anggaran (Rp)</th>
                    <th>Bulan</th>
                    <th>Keterangan Bukti</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result_kesejahteraan)) {
                    echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama_program']}</td>
                        <td>{$row['nama_kegiatan']}</td>
                        <td>{$row['sub_kegiatan']}</td>
                        <td>{$row['kd_rek']}</td>
                        <td>{$row['uraian']}</td>
                        <td>Rp " . number_format($row['total_anggaran'], 0, ',', '.') . "</td>
                        <td>{$row['bulan']}</td>
                        <td>{$row['keterangan_bukti']}</td>
                    </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>