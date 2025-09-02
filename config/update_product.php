<?php
session_start();
require 'conn.php'; // Sesuaikan dengan koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $kategori = trim($_POST['kategori']);
    $image = '';

    // Ambil data lama
    $query = "SELECT image FROM produk WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($old_image);
    $stmt->fetch();
    $stmt->close();

    // Cek apakah ada file baru
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../produk/";
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($imageFileType, ["jpg", "jpeg", "png"])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                if (!empty($old_image) && file_exists($target_dir . $old_image)) {
                    unlink($target_dir . $old_image);
                }
                $image = $file_name;
            }
        }
    } else {
        $image = $old_image;
    }

    // Update ke database
    $query = "UPDATE produk SET nama = ?, deskripsi = ?, kategori = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nama, $deskripsi, $kategori, $image, $id);

    if ($stmt->execute()) {
        $_SESSION['alert'] = "success|Data berhasil diperbarui!";
    } else {
        $_SESSION['alert'] = "error|Gagal memperbarui data!";
    }

    $stmt->close();
    header("Location: ../admin/product.php?status=edit_success");
    exit();
    
}
?>
