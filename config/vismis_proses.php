<?php
require "conn.php"; // Pastikan koneksi database sudah benar
session_start(); // Gunakan session yang sudah ada

// ✅ Perbarui Visi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateVisi"])) {
    $visi = $conn->real_escape_string($_POST["visi"]);
    $conn->query("DELETE FROM visi"); // Hapus visi lama
    $conn->query("INSERT INTO visi (visi) VALUES ('$visi')");

    $_SESSION['pesan'] = "Visi berhasil diperbarui!";
    $_SESSION['tipe'] = "success";
    header("Location: ../admin/vismis.php"); // Redirect ke halaman utama
    exit();
}

// ✅ Tambah Misi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addMisi"])) {
    $misi = $conn->real_escape_string($_POST["misi"]);
    $conn->query("INSERT INTO misi (misi) VALUES ('$misi')");

    $_SESSION['pesan'] = "Misi berhasil ditambahkan!";
    $_SESSION['tipe'] = "success";
    header("Location: ../admin/vismis.php");
    exit();
}

// ✅ Edit Misi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editMisi"])) {
    $id = intval($_POST["misiId"]);
    $misiBaru = $conn->real_escape_string($_POST["misiBaru"]);
    $conn->query("UPDATE misi SET misi = '$misiBaru' WHERE id = $id");

    $_SESSION['pesan'] = "Misi berhasil diperbarui!";
    $_SESSION['tipe'] = "success";
    header("Location: ../admin/vismis.php");
    exit();
}

// ✅ Hapus Misi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteMisi"])) {
    $id = intval($_POST["misiId"]); // Pastikan ID adalah angka
    $query = "DELETE FROM misi WHERE id = $id";
    
    if ($conn->query($query) === TRUE) {
        $_SESSION['pesan'] = "Misi berhasil dihapus!";
        $_SESSION['tipe'] = "warning";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus misi: " . $conn->error;
        $_SESSION['tipe'] = "error";
    }

    header("Location: ../admin/vismis.php");
    exit();
}
?>
