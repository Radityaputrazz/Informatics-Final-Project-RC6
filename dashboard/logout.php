<?php
    // Memulai session agar bisa menghapusnya
    session_start();

    // Menghapus semua variabel session
    session_unset();

    // Menghancurkan session yang ada
    session_destroy();

    // Menampilkan pesan sukses dan mengalihkan ke halaman login
    echo "<script>
            alert('Anda Berhasil Keluar dari Sistem...'); 
            window.location.href='../index.php';
          </script>";
    
    // Memastikan skrip berhenti sepenuhnya
    exit();
?>