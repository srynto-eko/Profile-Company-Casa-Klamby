<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "../config/conn.php";
session_start();
if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit();
}

// Tambah FAQ
if (isset($_POST['tambah_faq'])) {
    $tanya = trim($_POST['tanya']);
    $jawab = trim($_POST['jawab']);

    if (!empty($tanya) && !empty($jawab)) {
        $stmt = $conn->prepare("INSERT INTO faq (tanya, jawab) VALUES (?, ?)");
        $stmt->bind_param("ss", $tanya, $jawab);

        if ($stmt->execute()) {
            header("Location: faq.php?status=faq_success");
            exit();
        } else {
            header("Location: faq.php?status=faq_error");
            exit();
        }
    } else {
        header("Location: faq.php?status=faq_empty");
        exit();
    }
}

// Edit FAQ
if (isset($_POST['edit_faq'])) {
    $id = $_POST['id'];
    $tanya = trim($_POST['tanya']);
    $jawab = trim($_POST['jawab']);

    if (!empty($tanya) && !empty($jawab) && !empty($id)) {
        $query = "UPDATE faq SET tanya = ?, jawab = ? WHERE id = ?";
        $edit = $conn->prepare($query);
        $edit->bind_param("ssi", $tanya, $jawab, $id);

        if ($edit->execute()) {
            header("Location: faq.php?status=edit_success");
            exit();
        } else {
            header("Location: faq.php?status=edit_error");
            exit();
        }
    } else {
        header("Location: faq.php?status=edit_empty");
        exit();
    }
}

// Hapus FAQ
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $hapus = $conn->prepare("DELETE FROM faq WHERE id = ?");
    $hapus->bind_param("i", $id);

    if ($hapus->execute()) {
        header("Location: faq.php?status=delete_success");
        exit();
    } else {
        header("Location: faq.php?status=delete_error");
        exit();
    }
}

// Ambil data FAQ dari database
$result = $conn->query("SELECT * FROM faq ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Faq | Casa Klamby </title>

    <link rel="shortcut icon" href="../assets/compiled/png/image.png" type="image/x-icon">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/table-datatable-jquery.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/extra-component-sweetalert.css">
    <link rel="stylesheet" href="../assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../assets/extensions/sweetalert2/sweetalert2.min.css">
</head>
  
  <body>
  <?php
    // Menampilkan SweetAlert jika ada status di U  RL
    if (isset($_GET['status'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {";
        
        if ($_GET['status'] == "faq_success") {
            echo "Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'FAQ berhasil ditambahkan!',
                showConfirmButton: false,
                timer: 2000
            });";
        } elseif ($_GET['status'] == "faq_error") {
            echo "Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal menambahkan FAQ!',
                showConfirmButton: true
            });";
        } 

        echo "});
        </script>";
    }
    ?>
      <script src="../assets/static/js/initTheme.js"></script>
    <div id="app">
    <div id="sidebar">
            <div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="../index.php"><img src="../assets/static/images/logo/favicon.svg" style="height: 30px; margin-bottom: 8px; margin-right: 2px;" alt="Logo">Admin</a>
            </div>
            <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" class="iconify iconify--system-uicons" width="20" height="20"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                    <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                            opacity=".3"></path>
                        <g transform="translate(-210 -1)">
                            <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                            <circle cx="220.5" cy="11.5" r="4"></circle>
                            <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                        </g>
                    </g>
                </svg>
                <div class="form-check form-switch fs-6">
                    <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                    <label class="form-check-label"></label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                    viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                    </path>
                </svg>
            </div>
            <div class="sidebar-toggler  x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
        <div class="sidebar-menu">
        <ul class="menu">
            <li class="sidebar-title">Menu</li>
            
            <li
                class="sidebar-item">
                <a href="dashboard.php" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li
                class="sidebar-item ">
                <a href="vismis.php" class='sidebar-link'>
                    <i class="bi bi-emoji-smile-fill"></i>
                    <span>Visi Misi</span>
                </a>
            </li>
            <li
                class="sidebar-item ">
                <a href="product.php" class='sidebar-link'>
                    <i class="bi bi-collection-fill"></i>
                    <span>Product</span>
                </a>
            </li>
            <li
                class="sidebar-item active">
                <a href="faq.php" class='sidebar-link'>
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Faq</span>
                </a>
            </li>
            <li
                class="sidebar-item ">
                <a href="contact.php" class='sidebar-link'>
                    <i class="bi bi-journal-check"></i>
                    <span>Contact</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="logout.php" class='sidebar-link'>
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Frequently Asked Questions</h3>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addFaqModal">Tambah FAQ</button>
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th class="text-wrap" style="max-width: 10px;">No</th>
                                <th class="text-wrap" style="max-width: 100px;">Pertanyaan</th>
                                <th class="text-wrap" style="max-width: 100px;">Jawaban</th>
                                <th class="text-wrap" style="max-width: 50px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['tanya']) ?></td>
                                    <td><?= htmlspecialchars($row['jawab']) ?></td>
                                    <td>
                                        <button class="btn btn-primary editFaqBtn" 
                                                data-id="<?= $row['id'] ?>" 
                                                data-tanya="<?= htmlspecialchars($row['tanya']) ?>" 
                                                data-jawab="<?= htmlspecialchars($row['jawab']) ?>" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editFaqModal">Edit</button>
                                        <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-danger deleteFaqBtn">Hapus</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Tables end -->
    <!-- Modal Tambah FAQ -->
    <div class="modal fade" id="addFaqModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="tambah_faq">
                        <div class="mb-3">
                            <label>Pertanyaan</label>
                            <input type="text" name="tanya" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Jawaban</label>
                            <textarea name="jawab" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit FAQ -->
    <div class="modal fade" id="editFaqModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="edit_faq">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label>Pertanyaan</label>
                            <input type="text" id="edit-tanya" name="tanya" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Jawaban</label>
                            <textarea id="edit-jawab" name="jawab" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
    <script src="../assets/static/js/components/dark.js"></script>
    <script src="../assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>   
    <script src="../assets/compiled/js/app.js"></script>   
    <script src="../assets/extensions/jquery/jquery.min.js"></script>
    <script src="../assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/static/js/pages/datatables.js"></script>
    <script src="../assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="../assets/static/js/pages/sweetalert2.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let editButtons = document.querySelectorAll('.editFaqBtn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    let id = this.getAttribute('data-id');
                    let tanya = this.getAttribute('data-tanya');
                    let jawab = this.getAttribute('data-jawab');

                    document.getElementById('edit-id').value = id;
                    document.getElementById('edit-tanya').value = tanya;
                    document.getElementById('edit-jawab').value = jawab;
                });
            });

            // 🔹 Tambahkan Konfirmasi Sebelum Hapus
            let deleteButtons = document.querySelectorAll('.btn-danger');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah link langsung menghapus
                    
                    let deleteUrl = this.getAttribute('href'); // Ambil URL penghapusan
                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: "Data FAQ yang dihapus tidak bisa dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = deleteUrl; // Redirect ke URL hapus jika dikonfirmasi
                        }
                    });
                });
            });

            // 🔹 Notifikasi Setelah Aksi
            let urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                let status = urlParams.get('status');
                let message = "";
                let icon = "";

                if (status === "faq_success") {
                    message = "FAQ berhasil ditambahkan!";
                    icon = "success";
                } else if (status === "edit_success") {
                    message = "FAQ berhasil diperbarui!";
                    icon = "success";
                } else if (status === "delete_success") {
                    message = "FAQ berhasil dihapus!";
                    icon = "success";
                } else {
                    message = "Terjadi kesalahan!";
                    icon = "error";
                }

                Swal.fire({ icon: icon, title: "Notifikasi", text: message, timer: 2000 });
            }
        });
    </script>

</body>
</html>