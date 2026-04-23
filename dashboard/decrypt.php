<?php
session_start();
include "../config.php"; 

// Proteksi halaman
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='../login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dekripsi Berkas - The Bellagio Mansion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .container { padding-top: 50px; padding-bottom: 50px; }
        
        /* Card Styling */
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; }
        .card-header { background: linear-gradient(45deg, #1e3a8a, #2563eb); color: white; padding: 20px; border: none; }
        .card-header h4 { font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 1.1rem; }

        /* Table Styling */
        .table thead { background-color: #f1f5f9; }
        .table thead th { border: none; color: #475569; text-transform: uppercase; font-size: 12px; }
        .badge { border-radius: 6px; padding: 6px 10px; font-weight: 600; }

        /* Form Input */
        .form-control { border-radius: 8px; border: 1px solid #e2e8f0; padding: 12px; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

        /* Button Styling */
        .btn-decrypt { background: linear-gradient(45deg, #10b981, #059669); border: none; border-radius: 8px; color: white; font-weight: 600; transition: 0.3s; padding: 12px; }
        .btn-decrypt:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4); color: white; }

        .back-link { color: #64748b; text-decoration: none; transition: 0.3s; font-size: 14px; }
        .back-link:hover { color: #1e3a8a; }

        /* Radio Button Styling */
        input[type="radio"] { transform: scale(1.2); cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="mb-4">
                <a href="index.php" class="back-link">
                    <i class="fa fa-arrow-left mr-1"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="card">
                <div class="card-header text-center">
                    <div class="mb-2"><i class="fa fa-unlock-alt fa-2x"></i></div>
                    <h4>Form Dekripsi Berkas RC6</h4>
                </div>
                <div class="card-body p-4">
                    <form action="decrypt-process.php" method="POST">
                        <p class="text-muted mb-4 small"><i class="fa fa-info-circle mr-1"></i> Pilih satu berkas di bawah ini untuk dikembalikan ke format asli.</p>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="8%" class="text-center">Pilih</th>
                                        <th>Nama Berkas</th>
                                        <th>Ukuran</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = mysqli_query($connect, "SELECT * FROM file ORDER BY id_file DESC");
                                    if (mysqli_num_rows($sql) > 0) {
                                        while($r = mysqli_fetch_array($sql)) {
                                            ?>
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <input type="radio" name="fileid" value="<?php echo $r['id_file']; ?>" required>
                                                </td>
                                                <td class="align-middle text-dark"><strong><?php echo $r['file_name_source']; ?></strong></td>
                                                <td class="align-middle"><?php echo number_format($r['file_size'] / 1024, 2) . " KB"; ?></td>
                                                <td class="align-middle">
                                                    <?php if($r['status'] == 'Terdekripsi'): ?>
                                                        <span class="badge badge-light text-success"><i class="fa fa-check-circle mr-1"></i> Terdekripsi</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning text-dark"><i class="fa fa-lock mr-1"></i> Terenkripsi</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="align-middle">
                                                    <span class="text-muted small italic">
                                                        <?php echo !empty($r['keterangan']) ? htmlspecialchars($r['keterangan']) : '<em>(Tanpa Keterangan)</em>'; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Belum ada data file tersedia.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-5 justify-content-center">
                            <div class="col-md-7">
                                <div class="bg-light p-4 rounded shadow-sm">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark"><i class="fa fa-key mr-2"></i>Kata Sandi Dekripsi</label>
                                        <input type="password" name="pwdfile" class="form-control" placeholder="Masukkan password yang benar..." required>
                                        <small class="text-muted mt-2 d-block">Sistem akan mencocokkan password dengan database sebelum memproses file.</small>
                                    </div>
                                    <button type="submit" name="decrypt_now" class="btn btn-decrypt btn-block shadow">
                                        <i class="fa fa-refresh mr-2"></i> Proses Dekripsi Sekarang
                                    </button>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="history.php" class="back-link"><i class="fa fa-history mr-1"></i> Lihat Riwayat Berkas</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <footer class="text-center mt-5 text-muted small">
                &copy; <?php echo date('Y'); ?> THE BELLAGIO MANSION — INFORMATICS ENGINEERING
            </footer>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>