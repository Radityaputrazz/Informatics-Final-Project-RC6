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
        /* 1. Header & Logo Styling */
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

        /* 2. Sidebar & User Info */
        .main-sidebar { background-color: #0f172a !important; }
        
        .user-panel { 
            border-bottom: 1px solid rgba(255,255,255,0.05); 
            padding: 15px 10px; 
            display: flex;
            align-items: center;
        }

        .user-panel .image { 
            margin-right: 10px; 
            flex-shrink: 0; 
        }

        .user-panel .image img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #3b82f6; 
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .user-panel .image img:hover { transform: scale(1.1); }

        .user-panel .info p { 
            color: #fff; 
            font-weight: 600; 
            margin: 0; 
            font-size: 12.5px; 
            line-height: 1.2;
        }
        .user-panel .info .designation { 
            color: #94a3b8; 
            font-size: 10px; 
            display: block;
            margin-top: 2px;
        }

        /* 3. Dashboard Styling */
        .content-wrapper { background-color: #f8fafc; }
        .widget-small { border-radius: 12px; transition: 0.3s; }
        
        .card-welcome {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            padding: 40px;
            margin-top: 20px;
            border: none;
        }

        .welcome-logo {
            max-width: 120px; /* Ukuran logo dashboard */
            margin-bottom: 20px;
        }

        /* 4. Image Preview Modal */
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
            max-width: 70%; 
            max-height: 70%; 
            border-radius: 12px; 
            border: 3px solid #fff;
        }
        .close-overlay {
            position: absolute;
            top: 20px; right: 30px;
            color: #fff; font-size: 40px; text-decoration: none;
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
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-user-circle-o fa-lg"></i></a>
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
                        <span class="designation"><?php echo htmlspecialchars($data['job_title']); ?></span>
                    </div>
                </div>
                
                <ul class="sidebar-menu">
                    <li class="active"><a href="index.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
                    
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
                    <li><a href="help.php"><i class="fa fa-info-circle"></i><span>Bantuan</span></a></li>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">
            <div class="page-title">
                <div>
                    <h1><i class="fa fa-line-chart text-primary"></i> Statistik Sistem</h1>
                    <p class="text-muted">Halo <strong><?php echo htmlspecialchars($data['fullname']); ?></strong>, selamat datang kembali.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="widget-small info"><i class="icon fa fa-users fa-3x"></i>
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
                    <div class="widget-small danger"><i class="icon fa fa-lock fa-3x"></i>
                        <div class="info">
                            <?php 
                            $q_enc = mysqli_query($connect, "SELECT count(*) as total FROM file WHERE status='Terenkripsi'"); 
                            $res_enc = mysqli_fetch_assoc($q_enc); 
                            ?>
                            <h4>Terenkripsi</h4>
                            <p><b><?php echo $res_enc['total']; ?></b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="widget-small primary"><i class="icon fa fa-unlock-alt fa-3x"></i>
                        <div class="info">
                            <?php 
                            $q_dec = mysqli_query($connect, "SELECT count(*) as total FROM file WHERE status='Terdekripsi'"); 
                            $res_dec = mysqli_fetch_assoc($q_dec); 
                            ?>
                            <h4>Terdekripsi</h4>
                            <p><b><?php echo $res_dec['total']; ?></b></p>
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
                            <span class="badge badge-info p-2">
                                <i class="fa fa-clock-o mr-1"></i> Sesi Aktif: <?php echo date('H:i'); ?> WIB
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="text-center p-20 mt-4">
                <p class="text-muted small">Copyright &copy; <?php echo date('Y'); ?> — THE BELLAGIO MANSION</p>
            </footer>
        </div>
    </div>

    <script src="../assets/js/jquery-2.1.4.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>