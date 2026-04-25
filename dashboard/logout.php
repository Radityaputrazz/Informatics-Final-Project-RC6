<?php
session_start();

// Jika ada permintaan logout (saat tombol 'Ya' diklik)
if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout — The Bellagio Mansion</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f1f5f9; /* Slate 100 agar senada dengan Dashboard */
            font-family: 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body>

<script>
    // Langsung jalankan fungsi konfirmasi saat file ini diakses
    Swal.fire({
        title: 'Konfirmasi Keluar',
        text: "Apakah Anda yakin ingin mengakhiri sesi ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1e3a8a', // Deep Blue (Sesuai tema Bellagio)
        cancelButtonColor: '#64748b',  // Slate 500
        confirmButtonText: 'Ya, Keluar Sekarang',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        background: '#ffffff',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan animasi loading singkat agar lebih 'smooth'
            Swal.fire({
                title: 'Sedang Keluar...',
                html: 'Menghapus sesi keamanan Anda.',
                timer: 1500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    // Alihkan ke file ini sendiri dengan parameter konfirmasi
                    window.location.href = 'logout.php?confirm=true';
                }
            });
        } else {
            // Jika batal, kembali ke dashboard
            window.location.href = 'index.php';
        }
    })
</script>

</body>
</html>