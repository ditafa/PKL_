<?php
include 'koneksi.php'; // Koneksi ke database

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_data'])) {
    $program = mysqli_real_escape_string($conn, $_POST['program']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $kegiatan = mysqli_real_escape_string($conn, $_POST['kegiatan']);
    $kode_sub_kegiatan = mysqli_real_escape_string($conn, $_POST['kode_sub_kegiatan']);
    $sub_kegiatan = mysqli_real_escape_string($conn, $_POST['sub_kegiatan']);
    $sub_kode_kegiatan = mysqli_real_escape_string($conn, $_POST['sub_kode_kegiatan']);
    $kode_rekening = mysqli_real_escape_string($conn, $_POST['kode_rekening']);
    $uraian = mysqli_real_escape_string($conn, $_POST['uraian']);
    $anggaran = intval($_POST['anggaran']);
    $realisasi_gu = intval($_POST['realisasi_gu']); // Ambil realisasi anggaran
    $sisa_spd = $anggaran - $realisasi_gu; // Hitung sisa anggaran
    $keterangan_bukti = mysqli_real_escape_string($conn, $_POST['keterangan_bukti']); // Ambil keterangan bukti

    $sql = "INSERT INTO anggaran_kas (program, tanggal, kegiatan, kode_sub_kegiatan, sub_kegiatan, sub_kode_kegiatan, kode_rekening, uraian, anggaran, realisasi_gu, sisa_spd, keterangan_bukti)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssiiss", $program, $tanggal, $kegiatan, $kode_sub_kegiatan, $sub_kegiatan, $sub_kode_kegiatan, $kode_rekening, $uraian, $anggaran, $realisasi_gu, $sisa_spd, $keterangan_bukti);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data berhasil disimpan!'); window.location.href='dashboard1.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Ambil data dari database
$sql = "SELECT * FROM anggaran_kas";
$result = mysqli_query($conn, $sql);

// Proses pencarian
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_POST['search']);
    $sql = "SELECT * FROM anggaran_kas 
            WHERE program LIKE '%$search_query%' 
            OR tanggal LIKE '%$search_query%' 
            OR kegiatan LIKE '%$search_query%' 
            OR kode_sub_kegiatan LIKE '%$search_query%' 
            OR sub_kegiatan LIKE '%$search_query%' 
            OR kode_rekening LIKE '%$search_query%' 
            OR uraian LIKE '%$search_query%'";
    $result = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Kesejahteraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function(){
            $("#formInput").hide();

            $("#btnTambah").click(function(){
                $("#formInput").slideDown();
                $(this).hide();
            });

            // Aktifkan Datepicker
            $('#tanggal').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });

            // Inisialisasi Select2
            $('select').select2({
                placeholder: "Pilih...",
                allowClear: true
            });
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Data Kesejahteraan Rakyat</h2>
        <button id="btnTambah" class="btn btn-success mb-3">Tambahkan Data Baru</button>

        <div id="formInput">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Program</label>
                    <select name="program" class="form-control" required>
                        <option value="">Pilih Program</option>
                        <option value="Program PEMERINTAHAN DAN KESEJAHTERAAN RAKYAT">Program PEMERINTAHAN DAN KESEJAHTERAAN RAKYAT</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="text" id="tanggal" name="tanggal" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kegiatan</label>
                    <select name="kegiatan" class="form-control" required>
                        <option value="">Pilih Kegiatan</option>
                        <option value="Pelaksanaan Kebijakan Kesejahteraan Rakyat">Pelaksanaan Kebijakan Kesejahteraan Rakyat</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Kode Sub Kegiatan</label>
                    <select name="kode_sub_kegiatan" class="form-control" required>
                        <option value="">Pilih Sub Kegiatan</option>
                        <option value="4.01.02.2.02.0001">4.01.02.2.02.0001</option>
                        <option value="4.01.02.2.02.0002">4.01.02.2.02.0002</option>
                        <option value="4.01.02.2.02.0003">4.01.02.2.02.0003</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sub Kegiatan</label>
                    <select name="sub_kegiatan" class="form-control" required>
                        <option value="">Pilih Sub Kegiatan</option>
                        <option value="Fasilitas Pengelolaan Bina Mental Spiritual">Fasilitas Pengelolaan Bina Mental Spiritual</option>
                        <option value="Pelaksanaan Kebijakan, Evaluasi, dan Pencapaian Kinerja Terkait Kesejahteraan Sosial">Pelaksanaan Kebijakan, Evaluasi, dan Pencapaian Kinerja Terkait Kesejahteraan Sosial</option>
                        <option value="Pelaksanaan Kebijakan, Evaluasi, dan Pencapaian Kinerja Terkait Kesejahteraan Masyarakat">Pelaksanaan Kebijakan, Evaluasi, dan Pencapaian Kinerja Terkait Kesejahteraan Masyarakat</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kode Rekening</label>
                    <select name="kode_rekening" class="form-control" required>
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
                    <select name="uraian" class="form-control" required>
                        <option value="">Uraian Kegiatan</option>
                        <option value="Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Cetak">Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Cetak</option>
                        <option value="Belanja Alat/Bahan untuk Kegiatan Kantor-Kertas dan Cover">Belanja Alat/Bahan untuk Kegiatan Kantor-Kertas dan Cover</option>
                        <option value="Belanja Alat/Bahan untuk Kegiatan Kantor-Suvebir/Cendera Mata">Belanja Alat/Bahan untuk Kegiatan Kantor-Suvebir/Cendera Mata</option>
                        <option value="Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor">Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor</option>
                        <option value="Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Komputer">Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Komputer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Anggaran (Rp)</label>
                    <input type="number" name="anggaran" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Realisasi GU (Rp)</label>
                    <input type="number" name="realisasi_gu" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan Bukti</label>
                    <input type="text" name="keterangan_bukti" class="form-control" required>
                </div>

                <button type="submit" name="submit_data" class="btn btn-primary">Simpan</button>
            </form>
        </div>

        <hr>

        <h3>Cari Data</h3>
        <form method="POST" class="mb-3">
            <input type="text" name="search" class="form-control" placeholder="Masukkan kata kunci..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-info mt-2">Cari</button>
        </form>

        <h3>Data yang Sudah Dimasukkan</h3>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Program</th>
                    <th>Tanggal</th>
                    <th>Kegiatan</th>
                    <th>Kode Sub Kegiatan</th>
                    <th>Sub Kegiatan</th>
                    <th>Sub Kode Kegiatan</th>
                    <th>Kode Rekening</th>
                    <th>Uraian</th>
                    <th>Anggaran (Rp)</th>
                    <th>Realisasi GU (Rp)</th>
                    <th>Sisa Anggaran (Rp)</th>
                    <th>Keterangan Bukti</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['program']}</td>
                        <td>{$row['tanggal']}</td>
                        <td>{$row['kegiatan']}</td>
                        <td>{$row['kode_sub_kegiatan']}</td>
                        <td>{$row['sub_kegiatan']}</td>
                        <td>{$row['sub_kode_kegiatan']}</td>
                        <td>{$row['kode_rekening']}</td>
                        <td>{$row['uraian']}</td>
                        <td>Rp " . number_format($row['anggaran'], 0, ',', '.') . "</td>
                        <td>Rp " . number_format($row['realisasi_gu'], 0, ',', '.') . "</td>
                        <td>Rp " . number_format($row['sisa_spd'], 0, ',', '.') . "</td>
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