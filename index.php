<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'welcome';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Anggaran Kas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</head>
<body>
    <button id="toggleMenu" class="toggle-btn">☰</button>
    <div class="sidebar p-3">
        <h4 class="text-center">Anggaran Kas</h4>
        <a href="#" id="menuBagian">Bagian &#9662;</a>
        <div id="submenuBagian" class="submenu">
            <a href="?page=dashboard1">Bagian Kesejahteraan Rakyat</a>
            <a href="?page=dashboard2">Bagian Perencanaan Keuangan</a>
            <a href="?page=dashboard3">Bagian Organisasi</a>
            <a href="?page=dashboard5">Bagian Hukum</a>
            <a href="?page=dashboard6">Bagian Umum dan Protokol</a>
            <a href="?page=dashboard7">Tata Pemerintahan</a>
            <a href="?page=dashboard8">Bagian Perekonomian, Pembangunan, dan Sumber Daya Alam</a>
        </div>
        <a href="#" id="menuLaporan">Laporan &#9662;</a>
        <div id="submenuLaporan" class="submenu">
            <a href="#">LPJ Bagian Kesejahteraan Rakyat</a>
            <a href="#">LPJ Bagian Perencanaan Keuangan</a>
            <a href="#">LPJ Bagian Organisasi</a>
            <a href="#">LPJ Bagian Hukum</a>
            <a href="#">LPJ Bagian Umum dan Protokol</a>
            <a href="#">LPJ Tata Pemerintahan</a>
            <a href="#">LPJ Bagian Perekonomian, Pembangunan, dan Sumber Daya Alam</a>
            <a href="#">LPJ Setda</a>
        </div>

        <!-- Link ke halaman Input Anggaran -->
        <a href="?page=input_angkas">Input Anggaran</a>
        <a href="?page=data_angkas">Data Anggaran Kas</a>
        <a href="?page=data_tersimpan">Data Tersimpan</a>
    </div>

    <main class="content-container">
        <?php
        if ($page == 'welcome') {
            echo '
            <div class="welcome active">
                <img src="logo.png" alt="Logo Pemerintahan" class="logo">
                <h1>Selamat Datang</h1>
                <h3>Perencanaan dan Keuangan Sekretaris Daerah Bantul</h3>
            </div>';
        } else {
            $file = $page . ".php";
            if (file_exists($file)) {
                include($file);
            } else {
                echo "<h3>Halaman tidak ditemukan!</h3>";
            }
        }
        ?>
    </main>
</body>
</html>