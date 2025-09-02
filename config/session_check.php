<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id_admin'])) {
    // Redirect ke halaman login
    header("Location: ../admin/login.php");
    exit();
}
    
// Perbarui aktivitas terakhir (buat sesi timeout otomatis)
$timeout = 1800; // 30 menit
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: ../admin/login.php?timeout=1");
    exit();
}

$_SESSION['last_activity'] = time(); // Perbarui waktu aktivitas terakhir
?>
