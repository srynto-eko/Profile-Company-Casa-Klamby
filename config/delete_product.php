<?php
require "conn.php";
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['image'])) {
    $id = $_GET['id'];
    $image = $_GET['image'];
    $file_path = "../produk/" . $image;

    $stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if (!empty($image) && file_exists($file_path)) {
            unlink($file_path);
        }
        header("Location: ../admin/product.php?status=delete_success");
    } else {
        header("Location: ../admin/product.php?status=delete_error");
    }

    $stmt->close();
    exit();
} else {
    header("Location: ../admin/product.php");
    exit();
}
?>
