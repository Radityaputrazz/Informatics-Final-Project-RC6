<?php
session_start();
include "../config.php";

// Cek session untuk keamanan
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
    <title>Enkripsi Berkas - The Bellagio Mansion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        body { 
            background-color: #f8fafc; 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        
        .container { padding-top: 80px; }
        
        /* Card Styling */
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* Header dengan Gradient */
        .card-header { 
            background: linear-gradient(45deg, #1e3a8a, #2563eb); 
            color: white; 
            border: none;
            padding: 20px;
        }
        
        .card-header h4 { 
            margin-bottom: 0; 
            font-weight: 700; 
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 1.1rem;
        }

        /* Form Styling */
        .form-group label {
            font-weight: 600;
            color: #475569;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Button Styling */
        .btn-encrypt {
            background: linear-gradient(45deg, #2563eb, #1d4ed8);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-encrypt:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
            color: white;
        }

        .back-link {
            color: #64748b;
            text-decoration: none;
            transition: 0.3s;
            font-size: 14px;
        }

        .back-link:hover {
            color: #1e3a8a;
            text-decoration: none;
        }

        .icon-box {
            background: rgba(255,255,255,0.2);
            width: 50px;
            height: 50px;
            line-height: 50px;
            border-radius: 50%;
            margin: 0 auto 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <div class="text-center mb-4">
                <a href="index.php" class="back-link">
                    <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="card">
                <div class="card-header text-center">
                    <div class="icon-box">
                        <i class="fa fa-lock fa-lg"></i>
                    </div>
                    <h4>Enkripsi Berkas RC6</h4>
                </div>
                
                <div class="card-body p-4">
                    <form action="encrypt-process.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label><i class="fa fa-file-o mr-2"></i>Pilih File</label>
                            <input type="file" name="file" class="form-control-file p-2 border rounded w-100" required>
                            <small class="text-muted">Pastikan format file sesuai.</small>
                        </div>

                        <div class="form-group">
                            <label><i class="fa fa-commenting-o mr-2"></i>Keterangan Berkas</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Dokumen Rahasia Perusahaan" required></textarea>
                        </div>

                        <div class="form-group">
                            <label><i class="fa fa-key mr-2"></i>Kata Sandi (Key)</label>
                            <input type="password" name="pwdfile" class="form-control" placeholder="Masukkan password pengaman..." required>
                            <small class="text-danger">*Simpan password ini untuk proses dekripsi.</small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" name="encrypt_now" class="btn btn-encrypt btn-block shadow-sm">
                                <i class="fa fa-shield mr-2"></i> Jalankan Enkripsi
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="history.php" class="back-link">
                                <i class="fa fa-history mr-1"></i> Lihat Riwayat Enkripsi
                            </a>
                        </div>

                    </form>
                </div>
            </div>
            
            <p class="text-center mt-5 text-muted" style="font-size: 12px; letter-spacing: 1px;">
                THE BELLAGIO MANSION &copy; <?php echo date('Y'); ?>
            </p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>