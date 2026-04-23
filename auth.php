<?php
session_start();

/**
 * 1. IMPORT KONFIGURASI
 * Menggunakan require_once lebih aman karena jika file config.php hilang, 
 * script akan berhenti dan memberikan pesan error yang jelas.
 */
require_once 'config.php';

// Pastikan variabel koneksi dari config.php bernama $connect
if (!isset($connect)) {
    die("Fatal Error: Variabel \$connect tidak ditemukan. Periksa file config.php Anda.");
}

/**
 * 2. VALIDASI REQUEST
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($username) || empty($password)) {
    $error = 'Username dan password wajib diisi.';
}

if (empty($error)) {
    /**
     * 3. PREPARED STATEMENT
     */
    $query = "SELECT username, password, status FROM users WHERE username = ? LIMIT 1";
    $stmt  = mysqli_prepare($connect, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user   = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        /**
         * 4. VERIFIKASI (MENGGUNAKAN MD5)
         */
        if ($user && md5($password) === $user['password']) {
            
            // Login Berhasil
            session_regenerate_id(true);
            $_SESSION['username'] = $user['username'];
            $_SESSION['status']   = $user['status'];

            $routes = [
                1 => 'dashboard/index.php',
                2 => 'dashboard/info.php'
            ];
            
            $destination = $routes[$user['status']] ?? 'dashboard/history.php';
            
            header('Location: ' . $destination);
            exit;

        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Gagal memproses permintaan ke database.';
    }
}

/**
 * 5. REDIRECT JIKA GAGAL
 */
$_SESSION['login_error'] = $error;
header('Location: index.php');
exit;