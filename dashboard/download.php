<?php
session_start();
if (isset($_SESSION["download"])) {
    $file = $_SESSION["download"];
    if (file_exists($file)) {
        if (ob_get_level()) ob_end_clean();
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"");
        header("Content-Length: " . filesize($file));
        readfile($file);
        unset($_SESSION["download"]);
        exit;
    }
}
echo "File tidak tersedia.";