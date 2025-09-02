<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt'); // Simpan error di file log

require "../config/conn.php";
session_start();

$alert = ""; // Variabel untuk menyimpan SweetAlert

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; // Password dari form

    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Gunakan password_verify() jika password sudah di-hash
        if (password_verify($password, $user['password'])) { 
            $_SESSION['id_admin'] = $user['id_admin'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_activity'] = time();

            // SweetAlert sukses dan redirect ke dashboard
            $alert = "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Login Berhasil!',
                                text: 'Anda akan dialihkan ke dashboard.',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = 'dashboard.php';
                            });
                        });
                      </script>";
        } else {
            // SweetAlert password salah
            $alert = "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Login Gagal!',
                                text: 'Password salah.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                      </script>";
        }
    } else {
        // SweetAlert username tidak ditemukan
        $alert = "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Login Gagal!',
                            text: 'Username tidak ditemukan.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                  </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Casa Klamby</title>
    <link rel="shortcut icon" href="../assets/compiled/png/image.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/compiled/css/app.css">
    <link rel="stylesheet" href="../assets/compiled/css/auth.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/extra-component-sweetalert.css">
    <link rel="stylesheet" href="../assets/extensions/sweetalert2/sweetalert2.min.css">
</head>
<body>

    <?= $alert; ?> <!-- Menampilkan SweetAlert jika ada pesan -->

    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="../index.php">
                            <h2>
                                <img src="../assets/static/images/logo/favicon.svg" style="height: 30px; margin-bottom: 9px; margin-right: 2px;" alt="Logo">
                                Admin
                            </h2>
                        </a>
                    </div>
                    <h1 class="auth-title">Log in</h1>

                    <!-- SweetAlert untuk session timeout -->
                    <?php
                    if (isset($_GET['timeout'])) {
                        echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    title: 'Sesi Habis!',
                                    text: 'Silakan login kembali.',
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                });
                            });
                        </script>";
                    }
                    ?>

                    <form action="" method="POST">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="username" class="form-control form-control-xl" placeholder="Username" required>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="password" class="form-control form-control-xl" placeholder="Password" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <img src="../assets/compiled/svg/grid.svg" style="height: 50%; margin-top: 20%; margin-left: 28%;" alt="GRID">
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="../assets/static/js/pages/sweetalert2.js"></script>
</body>
</html>
