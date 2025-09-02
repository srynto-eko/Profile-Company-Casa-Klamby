<?php
require "../config/conn.php";

$text1 = isset($_POST['text1']) ? $_POST['text1'] : '';
$text2 = isset($_POST['text2']) ? $_POST['text2'] : '';
$text3 = isset($_POST['text3']) ? $_POST['text3'] : '';

$stmt = $conn->prepare("UPDATE profile_text SET text1 = ?, text2 = ?, text3 = ? WHERE id = 1");
$stmt->bind_param("sss", $text1, $text2, $text3);

$response = [];

if ($stmt->execute()) {
    $response["status"] = "success";
    $response["message"] = "Teks berhasil diperbarui!";
} else {
    $response["status"] = "error";
    $response["message"] = "Gagal menyimpan teks.";
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
