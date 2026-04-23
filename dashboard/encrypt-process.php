<?php
session_start();
include "../config.php";
include "RC6.php";

if (isset($_POST['encrypt_now'])) {
    $password = $_POST['pwdfile'];
    $key_rc6 = substr(md5($password), 0, 16);
    
    // Pastikan menangkap keterangan dari form
    $keterangan = isset($_POST['keterangan']) ? mysqli_real_escape_string($connect, $_POST['keterangan']) : "";
    
    $file_name = $_FILES['file']['name']; 
    $file_tmp  = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size']; 
    $username  = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
    
    $final_name = time() . "_" . $file_name . ".rc6";
    $path_tujuan = "file_encrypt/" . $final_name;

    if (!is_dir('file_encrypt')) mkdir('file_encrypt', 0777, true);

    $rc6 = new RC6($key_rc6);
    $read = fopen($file_tmp, "rb");
    $write = fopen($path_tujuan, "wb");

    while (!feof($read)) {
        $buffer = fread($read, 16);
        if ($buffer !== false && strlen($buffer) > 0) {
            if (strlen($buffer) < 16) $buffer = str_pad($buffer, 16, "\0");
            fwrite($write, $rc6->encrypt($buffer));
        }
    }
    fclose($read); 
    fclose($write);

    $tgl = date("Y-m-d H:i:s");

    $query = "INSERT INTO file (
                username, 
                file_name_source, 
                file_name_finish, 
                file_url, 
                file_size, 
                password, 
                tgl_upload, 
                status, 
                keterangan
              ) VALUES (
                '$username', 
                '$file_name', 
                '$final_name', 
                '$path_tujuan', 
                '$file_size', 
                '$key_rc6', 
                '$tgl', 
                'Terenkripsi', 
                '$keterangan'
              )";
    
    if (mysqli_query($connect, $query)) {
        echo "<script>alert('Berhasil!'); window.location.href='encrypt.php';</script>";
    } else {
        die("Error: " . mysqli_error($connect));
    }
}