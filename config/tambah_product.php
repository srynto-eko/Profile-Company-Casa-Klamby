<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "../config/conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $kategori = trim($_POST['kategori']);

    // Pastikan input tidak kosong
    if (empty($nama) || empty($deskripsi) || empty($kategori)) {
        header("Location: ../admin/product.php?status=product_error");
        exit();
    }

    // Cek apakah ada file gambar yang diunggah
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../produk/"; // Folder penyimpanan
        $file_name = basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png"];

        // Validasi tipe file
        if (!in_array($imageFileType, $allowed_types)) {
            header("Location: ../admin/product.php?status=format_error");
            exit();
        }

        // Pastikan file gambar benar-benar gambar
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            header("Location: ../admin/product.php?status=upload_error");
            exit();
        }

        // Gunakan nama file unik agar tidak bentrok
        $image = time() . "_" . uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $image;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            header("Location: ../admin/product.php?status=upload_error");
            exit();
        }
    } else {
        header("Location: ../admin/product.php?status=no_file");
        exit();
    }

    // Simpan data produk ke database
    $stmt = $conn->prepare("INSERT INTO produk (nama, deskripsi, kategori, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $deskripsi, $kategori, $image);

    if ($stmt->execute()) {
        header("Location: ../admin/product.php?status=product_success");
    } else {
        header("Location: ../admin/product.php?status=product_error");
    }

    $stmt->close();
    exit();
}
?>
