<?php
require "../config/conn.php";

$result = $conn->query("SELECT text1, text2, text3 FROM profile_text WHERE id=1");

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row); // Kirim dalam format JSON
} else {
    echo json_encode(["text1" => "", "text2" => "", "text3" => ""]); // Kosongkan jika tidak ada data
}

$conn->close();
?>
