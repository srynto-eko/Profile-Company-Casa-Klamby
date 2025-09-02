<?php
session_start();
require 'conn.php'; // Sesuaikan dengan struktur proyek Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // ID data yang diedit
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $image = '';

    // Ambil data lama untuk mempertahankan gambar jika tidak diperbarui
    $query = "SELECT image FROM about WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($old_image);
    $stmt->fetch();
    $stmt->close();

    // Cek apakah ada file yang diunggah
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        $file_name = basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png"];

        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['alert'] = "error|Hanya file JPG, JPEG, & PNG yang diperbolehkan!";
            header("Location: about.php");
            exit();
        }

        // Gunakan nama unik untuk file baru
        $image = time() . "_" . uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $image;

        // Pindahkan file yang diunggah
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $_SESSION['alert'] = "error|Gagal mengunggah file!";
            header("Location: about.php");
            exit();
        }

        // Hapus gambar lama jika ada
        if (!empty($old_image) && file_exists($target_dir . $old_image)) {
            unlink($target_dir . $old_image);
        }
    } else {
        // Jika tidak ada file yang diunggah, gunakan gambar lama
        $image = $old_image;
    }

    // Update ke database
    $query = "UPDATE about SET nama = ?, deskripsi = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $nama, $deskripsi, $image, $id);

    if ($stmt->execute()) {
        $_SESSION['alert'] = "success|Data berhasil diperbarui!";
    } else {
        $_SESSION['alert'] = "error|Gagal memperbarui data!";
    }

    $stmt->close();
    header("Location: ../admin/about.php?status=about_success");
    exit();
    
}
?>
