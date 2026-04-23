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

// Ambil data user untuk profil sidebar
$user = $_SESSION['username'];
$query_user = mysqli_query($connect, "SELECT * FROM users WHERE username='$user'");
$data = mysqli_fetch_array($query_user);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/ubl.png">
    <title>Dashboard — <?php echo htmlspecialchars($data['fullname']); ?></title>
    
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        /* Modern Theme Overrides */
        .main-header .logo { background-color: #1e3a8a !important; font-weight: 700; letter-spacing: 0.5px; }
        .navbar { background-color: #2563eb !important; }
        .main-sidebar { background-color: #0f172a !important; }
        
        /* 1. Perbaikan User Info & Foto Profil (Dikecilkan agar lebih bulat) */
        .user-panel { 
            border-bottom: 1px solid #1e293b; 
            padding: 15px 10px; /* Padding dikurangi */
            display: flex;
            align-items: center;
        }
        
        .user-panel .image { 
            margin-right: 12px;
            flex-shrink: 0;
        }

        .user-panel .image img {
            width: 45px; /* Ukuran dikunci agar bulat sempurna */
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

        /* 2. Lightbox / Modal Preview (Klik Gambar) */
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

        /* Sidebar Menu Active */
        .sidebar-menu li.active a { 
            background-color: #1e40af !important; 
            border-left-color: #60a5fa !important; 
            color: #fff !important;
        }

        .content-wrapper { background-color: #f8fafc; }
        
        /* Widget & Card Styling */
        .widget-small { border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); overflow: hidden; transition: 0.3s; }
        .widget-small:hover { transform: translateY(-5px); box-shadow: 0 10px 15px rgba(0,0,0,0.05); }
        
        .card-welcome {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            padding: 40px;
            margin-top: 20px;
        }

        .welcome-logo {
            max-width: 180px;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.1));
            margin-bottom: 25px;
        }
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
            <img src="../assets/images/user.png" alt="Profile Detail">
        </div>

        <aside class="main-sidebar hidden-print">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="image">
                        <a href="#viewProfile">
                            <img src="../assets/images/user.png" alt="User Image">
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
                    <li class="active"><a href="index.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
                    
                    <?php if ($data['status'] == 1): ?>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-shield"></i><span>Layanan Kripto</span><i class="fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="encrypt.php"><i class="fa fa-lock"></i> Enkripsi Berkas</a></li>
                            <li><a href="decrypt.php"><i class="fa fa-unlock"></i> Dekripsi Berkas</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <li><a href="history.php"><i class="fa fa-folder-open"></i><span>Daftar Berkas</span></a></li>
                    <li><a href="help.php"><i class="fa fa-info-circle"></i><span>Bantuan</span></a></li>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">
            <div class="page-title">
                <div>
                    <h1><i class="fa fa-line-chart text-primary"></i> Statistik Sistem</h1>
                    <p class="text-muted">Halo <strong><?php echo $data['fullname']; ?></strong>, selamat datang di panel keamanan.</p>
                </div>
                <div>
                    <ul class="breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li class="active">Dashboard</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="widget-small info">
                        <i class="icon fa fa-users fa-3x"></i>
                        <div class="info">
                            <?php 
                            $q_user = mysqli_query($connect, "SELECT count(*) as total FROM users"); 
                            $res_user = mysqli_fetch_assoc($q_user); 
                            ?>
                            <h4>Total Pengguna</h4>
                            <p><b><?php echo $res_user['total']; ?></b></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="widget-small danger">
                        <i class="icon fa fa-lock fa-3x"></i>
                        <div class="info">
                            <?php 
                            $q_enc = mysqli_query($connect, "SELECT count(*) as total FROM file WHERE status='Terenkripsi'"); 
                            $res_enc = mysqli_fetch_assoc($q_enc); 
                            ?>
                            <h4>Terenkripsi</h4>
                            <p><b><?php echo $res_enc['total']; ?></b> Berkas</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="widget-small primary">
                        <i class="icon fa fa-unlock-alt fa-3x"></i>
                        <div class="info">
                            <?php 
                            $q_dec = mysqli_query($connect, "SELECT count(*) as total FROM file WHERE status='Terdekripsi'"); 
                            $res_dec = mysqli_fetch_assoc($q_dec); 
                            ?>
                            <h4>Terdekripsi</h4>
                            <p><b><?php echo $res_dec['total']; ?></b> Berkas</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="card-welcome">
                        <img src="../assets/images/bellman.png" alt="Bellagio Mansion" class="welcome-logo">
                        <h3 style="color: #1e3a8a; font-weight: 700;">The Bellagio Mansion Security System</h3>
                        <div class="mt-4">
                            <span class="badge badge-info p-2" style="font-weight: 500;">
                                <i class="fa fa-clock-o mr-1"></i> Aktivitas Terakhir: <?php echo date('d M Y, H:i', strtotime($data['last_activity'])); ?>
                            </span>
                        </div>
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