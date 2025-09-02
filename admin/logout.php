<?php
session_start();

if (isset($_SESSION['username'])) {
    session_unset();
    session_destroy();

    // Hapus cookie sesi
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
}

header("Location: login.php?logout=true");
exit();
?>
