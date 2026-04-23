<?php
session_start();
include('../config.php');

// Proteksi Session
if (empty($_SESSION['username'])) {
    header("location:../index.php");
    exit();
}

// Update Aktivitas Terakhir
$last = $_SESSION['username'];
$sqlupdate = "UPDATE users SET last_activity=now() WHERE username='$last'";
mysqli_query($connect, $sqlupdate);

// Ambil data user untuk profil
$user = $_SESSION['username'];
$query = mysqli_query($connect, "SELECT fullname, job_title, last_activity, status FROM users WHERE username='$user'");
$data = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/ubl.png">
    <title>Bantuan — <?php echo htmlspecialchars($data['fullname']); ?></title>
    
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        /* Modern Theme Overrides (Sama dengan Index) */
        .main-header .logo { 
            background-color: #1e3a8a !important; 
            font-weight: 700; 
            letter-spacing: 0.5px;
            font-size: 11pt !important;
            line-height: 50px;
            height: 50px;
            padding: 0 15px;
        }
        .navbar { background-color: #2563eb !important; }
        .main-sidebar { background-color: #0f172a !important; }
        
        /* Perbaikan User Info & Foto Profil (Konsisten dengan Index) */
        .user-panel { 
            border-bottom: 1px solid #1e293b; 
            padding: 15px 10px; 
            display: flex;
            align-items: center;
        }
        
        .user-panel .image { 
            margin-right: 12px;
            flex-shrink: 0;
        }

        .user-panel .image img {
            width: 45px; 
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #3b82f6;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .user-panel .image img:hover { transform: scale(1.1); }

        .user-panel .info { padding-left: 0; }
        .user-panel .info p { 
            margin-bottom: 2px; 
            font-size: 13px; 
            font-weight: 600; 
            color: #fff;
        }

        /* Lightbox / Modal Preview (Sama dengan Index) */
        .img-overlay {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.85);
            justify-content: center;
            align-items: center;
        }
        .img-overlay:target { display: flex; }
        .img-overlay img { 
            max-width: 80%; 
            max-height: 80%; 
            border-radius: 15px; 
            box-shadow: 0 0 30px rgba(0,0,0,0.5);
            border: 3px solid #fff;
        }
        .close-overlay {
            position: absolute;
            top: 20px; right: 30px;
            color: #fff; font-size: 40px; text-decoration: none;
        }

        /* Help Card Styling */
        .card { border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .help-item { padding: 20px; border-bottom: 1px solid #f1f5f9; transition: 0.3s; }
        .help-item:hover { background-color: #f8fafc; }
        .help-item:last-child { border-bottom: none; }
        
        .help-icon { 
            width: 42px; height: 42px; line-height: 42px; 
            text-align: center; border-radius: 10px; 
            background: #eff6ff; color: #2563eb; 
            margin-right: 15px; float: left; 
        }
        
        .instruction-step { font-weight: 600; color: #1e3a8a; display: block; margin-bottom: 5px; }
        .alert-custom { background-color: #fff7ed; border-left: 5px solid #f59e0b; color: #9a3412; border-radius: 8px; }
        
        .sidebar-menu li.active a { 
            background-color: #1e40af !important; 
            border-left-color: #60a5fa !important; 
            color: #fff !important;
        }

        .content-wrapper { background-color: #f8fafc; }
    </style>
</head>

<body class="sidebar-mini fixed">
    <div class="wrapper">
        <header class="main-header hidden-print">
            <a class="logo" href="index.php">The Bellagio Mansion</a>
            <nav class="navbar navbar-static-top">
                <a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
                <div class="navbar-custom-menu">
                    <ul class="top-nav">
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button">
                                <i class="fa fa-user-circle-o fa-lg"></i>
                            </a>
                            <ul class="dropdown-menu settings-menu">
                                <li><a href="logout.php"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <div id="viewProfile" class="img-overlay">
            <a href="#" class="close-overlay">&times;</a>
            <img src="../assets/images/Radit.png" alt="Profile Detail">
        </div>

        <aside class="main-sidebar hidden-print">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="image">
                        <a href="#viewProfile">
                            <img src="../assets/images/Radit.png" alt="User Image">
                        </a>
                    </div>
                    <div class="info">
                        <p><?php echo htmlspecialchars($data['fullname']); ?></p>
                        <span class="designation" style="color: #94a3b8; font-size: 10px; display: block;">
                            <?php echo htmlspecialchars($data['job_title']); ?>
                        </span>
                    </div>
                </div>
                
                <ul class="sidebar-menu">
                    <li><a href="index.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
                    
                    <?php if ($data['status'] == 1): ?>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-shield"></i><span>Layanan Kripto</span><i class="fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="encrypt.php"><i class="fa fa-lock"></i> Enkripsi</a></li>
                            <li><a href="decrypt.php"><i class="fa fa-unlock"></i> Dekripsi</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <li><a href="history.php"><i class="fa fa-folder-open"></i><span>Daftar Berkas</span></a></li>
                    <li class="active"><a href="help.php"><i class="fa fa-info-circle"></i><span>Bantuan</span></a></li>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">
            <div class="page-title">
                <div>
                    <h1><i class="fa fa-question-circle text-primary"></i> Panduan Sistem</h1>
                    <p class="text-muted">Pelajari cara mengamankan dokumen di platform ini.</p>
                </div>
                <div>
                    <ul class="breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li class="active">Bantuan</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="help-item clearfix">
                                <div class="help-icon"><i class="fa fa-th-large"></i></div>
                                <div style="overflow: hidden;">
                                    <span class="instruction-step">Dashboard Utama</span>
                                    <p class="text-muted small">Menampilkan statistik penggunaan sistem termasuk jumlah user dan file yang telah terproses.</p>
                                </div>
                            </div>

                            <div class="help-item clearfix">
                                <div class="help-icon"><i class="fa fa-lock"></i></div>
                                <div style="overflow: hidden;">
                                    <span class="instruction-step">Proses Enkripsi (RC6)</span>
                                    <p class="text-muted small">Menu untuk mengubah file asli menjadi format rahasia (.rc6) menggunakan password kunci pribadi Anda.</p>
                                </div>
                            </div>

                            <div class="help-item clearfix">
                                <div class="help-icon"><i class="fa fa-unlock"></i></div>
                                <div style="overflow: hidden;">
                                    <span class="instruction-step">Proses Dekripsi</span>
                                    <p class="text-muted small">Mengembalikan file .rc6 ke format aslinya. Dibutuhkan password yang sama persis saat proses enkripsi dilakukan.</p>
                                </div>
                            </div>

                            <div class="help-item clearfix">
                                <div class="help-icon"><i class="fa fa-history"></i></div>
                                <div style="overflow: hidden;">
                                    <span class="instruction-step">Daftar Berkas</span>
                                    <p class="text-muted small">Tempat untuk mengunduh hasil enkripsi/dekripsi serta melihat riwayat aktivitas berkas Anda.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 alert-custom shadow-sm">
                        <h5 class="mb-3"><i class="fa fa-warning"></i> Keamanan Password</h5>
                        <p style="font-size: 13px; line-height: 1.6;">
                            Sistem ini menggunakan algoritma **RC6**. Keamanan data Anda sepenuhnya bergantung pada password yang dibuat.
                            <br><br>
                            **Peringatan:** Password tidak disimpan di server. Jika hilang, data Anda tidak akan bisa dibuka kembali oleh siapapun.
                        </p>
                    </div>

                    <div class="text-center mt-4" style="opacity: 0.6;">
                        <img width="140" src="../assets/images/bellman.png" style="filter: grayscale(100%);">
                        <p class="text-muted mt-3 small">The Bellagio Mansion Security<br>System Version 2.0</p>
                    </div>
                </div>
            </div>

            <footer class="text-center p-20 mt-4">
                <p class="text-muted small" style="letter-spacing: 1px;">
                    Copyright &copy; <?php echo date('Y'); ?> — <strong>THE BELLAGIO MANSION</strong>
                </p>
            </footer>
        </div>
    </div>

    <script src="../assets/js/jquery-2.1.4.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>