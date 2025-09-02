<?php
$hashedPassword = password_hash("admin123", PASSWORD_BCRYPT);
echo $hashedPassword; // Menampilkan hash password
?>
