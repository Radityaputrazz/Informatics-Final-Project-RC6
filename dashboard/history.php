<?php
session_start();
include "../config.php"; 

// Proteksi halaman
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Berkas - The Bellagio Mansion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .container { padding-top: 40px; padding-bottom: 40px; }
        
        /* Header Styling */
        .page-header { margin-bottom: 30px; }
        .page-header h2 { font-weight: 700; color: #1e293b; }
        
        /* Card & Table Styling */
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; }
        .table { margin-bottom: 0; }
        .table thead { background-color: #1e3a8a; color: white; }
        .table thead th { border: none; font-weight: 600; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; vertical-align: middle; }
        .table tbody tr { transition: 0.3s; }
        .table tbody tr:hover { background-color: #f1f5f9; }
        
        /* Status Badge */
        .badge { border-radius: 6px; padding: 6px 10px; font-weight: 600; }
        .badge-success { background-color: #dcfce7; color: #166534; }
        .badge-warning { background-color: #fef9c3; color: #854d0e; }

        /* Action Buttons */
        .btn-action { border-radius: 8px; padding: 6px 12px; font-size: 13px; font-weight: 600; transition: 0.3s; }
        .btn-outline-danger:hover { background-color: #ef4444; color: white; }
        
        .back-link { color: #64748b; text-decoration: none; transition: 0.3s; font-size: 14px; margin-bottom: 20px; display: inline-block; }
        .back-link:hover { color: #1e3a8a; }
        
        .empty-state { padding: 40px; text-align: center; color: #94a3b8; }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="back-link">
        <i class="fa fa-arrow-left mr-1"></i> Kembali ke Dashboard
    </a>

    <div class="page-header d-flex justify-content-between align-items-center">
        <h2><i class="fa fa-history text-primary mr-2"></i>Riwayat Berkas</h2>
        <div>
            <a href="encrypt.php" class="btn btn-primary btn-action shadow-sm">
                <i class="fa fa-plus mr-1"></i> Enkripsi Baru
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-center">
                        <th width="5%">No</th>
                        <th class="text-left">Informasi Berkas</th>
                        <th>Path Sistem</th>
                        <th>Waktu Proses</th>
                        <th>Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = mysqli_query($connect, "SELECT * FROM file ORDER BY id_file DESC");
                    
                    if (mysqli_num_rows($sql) > 0) {
                        while($r = mysqli_fetch_array($sql)) {
                            ?>
                            <tr class="text-center">
                                <td class="font-weight-bold text-muted"><?php echo $no++; ?></td>
                                <td class="text-left">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-file-text-o fa-2x text-primary mr-3"></i>
                                        <div>
                                            <span class="d-block font-weight-bold text-dark"><?php echo htmlspecialchars($r['file_name_source']); ?></span>
                                            <small class="text-muted">Ukuran: <?php echo number_format($r['file_size'] / 1024, 2); ?> KB</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="small text-primary" style="background: #eff6ff; padding: 2px 5px; border-radius: 4px;">
                                        <?php echo $r['file_url']; ?>
                                    </code>
                                </td>
                                <td>
                                    <span class="small text-muted">
                                        <i class="fa fa-calendar-o mr-1"></i> <?php echo date('d M Y', strtotime($r['tgl_upload'])); ?><br>
                                        <i class="fa fa-clock-o mr-1"></i> <?php echo date('H:i', strtotime($r['tgl_upload'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($r['status'] == 'Terdekripsi'): ?>
                                        <span class="badge badge-success"><i class="fa fa-unlock mr-1"></i> Terdekripsi</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fa fa-lock mr-1"></i> Terenkripsi</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="delete.php?id=<?php echo $r['id_file']; ?>" 
                                       class="btn btn-outline-danger btn-action" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus riwayat berkas ini?')">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fa fa-folder-open-o fa-3x mb-3"></i>
                                <p>Belum ada riwayat aktivitas berkas saat ini.</p>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="text-center mt-5 text-muted small">
        &copy; <?php echo date('Y'); ?> THE BELLAGIO MANSION — SECURITY LOG SYSTEM
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>