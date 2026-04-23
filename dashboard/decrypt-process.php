<?php
session_start();
include "../config.php";
include "RC6.php";

if (isset($_POST['decrypt_now'])) {
    // 1. Ambil data dari form
    $idfile    = mysqli_real_escape_string($connect, $_POST['fileid']);
    $pwd_input = $_POST["pwdfile"];
    
    // Hash password input untuk dicocokkan dengan database
    $key_rc6   = substr(md5($pwd_input), 0, 16);

    // 2. Cari data file di database
    $query = mysqli_query($connect, "SELECT * FROM file WHERE id_file='$idfile'");
    $data  = mysqli_fetch_assoc($query);

    // 3. Validasi Password
    if ($data && $data['password'] == $key_rc6) {
        $path_asal  = $data["file_url"];
        $nama_asli  = $data["file_name_source"];
        
        // Buat folder hasil dekripsi jika belum ada
        if (!is_dir('file_decrypt')) {
            mkdir('file_decrypt', 0777, true);
        }
        
        $path_hasil = "file_decrypt/" . $nama_asli;

        // Inisialisasi RC6
        $rc6 = new RC6($key_rc6);
        $read_handle  = fopen($path_asal, "rb");
        $write_handle = fopen($path_hasil, "wb");

        // Proses pembacaan file terenkripsi
        $file_size = filesize($path_asal);
        $total_blocks = ceil($file_size / 16);

        for ($i = 0; $i < $total_blocks; $i++) {
            $block = fread($read_handle, 16);
            if (strlen($block) === 16) {
                $decrypted = $rc6->decrypt($block);
                
                // Hapus padding null (\0) hanya pada blok terakhir
                if ($i === ($total_blocks - 1)) {
                    $decrypted = rtrim($decrypted, "\0");
                }
                fwrite($write_handle, $decrypted);
            }
        }
        fclose($read_handle); 
        fclose($write_handle);

        // 4. Update Status (JANGAN UPDATE KETERANGAN agar tidak berubah jadi "Terarsip")
        $update_query = "UPDATE file SET status = 'Terdekripsi' WHERE id_file = '$idfile'";
        mysqli_query($connect, $update_query);

        // Simpan path untuk diunduh
        $_SESSION["download"] = $path_hasil;

        echo "<script>
                alert('Dekripsi Berhasil! File telah dikembalikan ke format asli.');
                window.open('download.php', '_blank');
                window.location.href='history.php';
              </script>";
    } else {
        echo "<script>
                alert('Password salah atau data tidak ditemukan!'); 
                window.history.back();
              </script>";
    }
}