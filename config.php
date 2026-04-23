<?php
// Pastikan tidak ada spasi atau baris kosong sebelum tag <?php di atas

define('DB_HOST',   'localhost');
define('DB_USER',   'root');
define('DB_PASS',   '');
define('DB_NAME',   'rc6_db');
define('DB_CHARSET','utf8mb4');

// NAMA VARIABEL HARUS $connect (Sesuai yang dipanggil di auth.php)
$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$connect) {
    error_log('Koneksi gagal: ' . mysqli_connect_error());
    die('Koneksi ke database gagal.');
}

mysqli_set_charset($connect, DB_CHARSET);

