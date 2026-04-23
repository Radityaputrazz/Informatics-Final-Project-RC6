<?php
session_start();
include('../config.php');

// 1. Proteksi Halaman
if(empty($_SESSION['username'])){
    header("location:../index.php");
    exit;
}

$user = $_SESSION['username'];

// 2. Update Aktivitas & Ambil Data Profil
mysqli_query($connect, "UPDATE users SET last_activity=NOW() WHERE username='$user'");

$stmt_user = mysqli_prepare($connect, "SELECT fullname, job_title, last_activity FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt_user, "s", $user);
mysqli_stmt_execute($stmt_user);
$profile = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_user));

// 3. Ambil Data File Berdasarkan ID
if (!isset($_GET['id_file'])) {
    header("location:decrypt.php");
    exit;
}

$id_file = mysqli_real_escape_string($connect, $_GET['id_file']);
$query_file = mysqli_query($connect, "SELECT * FROM file WHERE id_file='$id_file'");
$data_file = mysqli_fetch_array($query_file);

// Jika ID tidak ditemukan
if (!$data_file) {
    echo "<script>alert('Data file tidak ditemukan!'); window.location.href='decrypt.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/ubl.png">
    <title>Konfirmasi Dekripsi — <?php echo htmlspecialchars($profile['fullname']); ?></title>
    
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="sidebar-mini fixed">
<div class="wrapper">
    <header class="main-header hidden-print">
        <a class="logo" style="background-color: #2196F3; font-size: 13pt">The Bellagio Mansion</a>
        <nav class="navbar navbar-static-top" style="background-color: #2196F3">
            <a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
        </nav>
    </header>

    <aside class="main-sidebar hidden-print">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image"><img class="img-circle" src="../assets/images/user.png" alt="User Image"></div>
                <div class="pull-left info">
                    <p><?php echo htmlspecialchars($profile['fullname']); ?></p>
                    <p class="designation"><?php echo htmlspecialchars($profile['job_title']); ?></p>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="index.php"><i class="fa fa-home"></i><span>Dashboard</span></a></li>
                <li class="treeview active">
                    <a href="#"><i class="fa fa-file-o"></i><span>File</span><i class="fa fa-angle-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="encrypt.php"><i class="fa fa-lock"></i> Enkripsi</a></li>
                        <li class="active"><a href="decrypt.php"><i class="fa fa-unlock"></i> Dekripsi</a></li>
                    </ul>
                </li>
                <li><a href="history.php"><i class="fa fa-list-ol"></i><span>Daftar Berkas</span></a></li>
                <li><a href="logout.php"><i class="fa fa-sign-out"></i><span>Logout</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <div class="page-title">
            <div>
                <h1><i class="fa fa-unlock-alt"></i> Konfirmasi Dekripsi</h1>
                <p>Verifikasi kunci untuk membuka akses berkas asli</p>
            </div>
            <div>
                <ul class="breadcrumb">
                    <li><i class="fa fa-home fa-lg"></i></li>
                    <li><a href="decrypt.php">Dekripsi</a></li>
                    <li>Proses</li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">Detail File Terenkripsi</h4>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%" class="bg-light">Nama File Asli</th>
                                    <td><?php echo htmlspecialchars($data_file['file_name_source']); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Nama File Enkripsi</th>
                                    <td><code style="color: red;"><?php echo htmlspecialchars($data_file['file_name_finish']); ?></code></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Ukuran File</th>
                                    <td><?php echo number_format($data_file['file_size'], 2); ?> KB</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Waktu Enkripsi</th>
                                    <td><?php echo $data_file['tgl_upload']; ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Keterangan</th>
                                    <td><?php echo htmlspecialchars($data_file['keterangan']); ?></td>
                                </tr>
                            </table>
                        </div>

                        <hr>
                        
                        <form action="decrypt-process.php" method="post" class="form-horizontal">
                            <input type="hidden" name="fileid" value="<?php echo $data_file['id_file'];?>">
                            
                            <div class="form-group">
                                <label class="col-md-4 control-label">Masukkan Password Kunci</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                        <input class="form-control" type="password" name="pwdfile" placeholder="Password Enkripsi" required autofocus>
                                    </div>
                                    <small class="text-muted italic">*Kunci harus sama dengan saat melakukan enkripsi.</small>
                                </div>
                            </div>

                            <div class="text-center" style="margin-top: 20px;">
                                <button type="submit" name="decrypt_now" class="btn btn-info btn-lg">
                                    <i class="fa fa-refresh"></i> Mulai Dekripsi RC6
                                </button>
                                <a href="decrypt.php" class="btn btn-default btn-lg">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/jquery-2.1.4.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>