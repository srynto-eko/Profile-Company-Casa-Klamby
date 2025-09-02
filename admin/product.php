<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "../config/conn.php";
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // Ambil ID dari form
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $kategori = trim($_POST['kategori']);
    $image = '';

    // Pastikan input tidak kosong
    if (empty($nama) || empty($deskripsi) || empty($kategori)) {
        echo "<script>alert('Nama, deskripsi, dan kategori tidak boleh kosong!'); window.history.back();</script>";
        exit();
    }

    // Cek apakah ada file yang diunggah
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../produk/"; // Folder penyimpanan
        $file_name = basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png"];

        // Validasi tipe file
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>alert('Hanya file JPG, JPEG, & PNG yang diperbolehkan!'); window.history.back();</script>";
            exit();
        }

        // Pastikan file gambar benar-benar gambar
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('File yang diunggah bukan gambar!'); window.history.back();</script>";
            exit();
        }

        // Gunakan nama file unik agar tidak bentrok
        $image = time() . "_" . uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $image;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "<script>alert('Gagal mengunggah file!'); window.history.back();</script>";
            exit();
        }

        // Perbarui database dengan gambar baru
        $stmt = $conn->prepare("UPDATE produk SET nama=?, deskripsi=?, kategori=?, image=? WHERE id=?");
        $stmt->bind_param("sssi", $nama, $deskripsi, $kategori, $image, $id);
    } else {
        // Perbarui database tanpa mengganti gambar (pakai gambar lama)
        $stmt = $conn->prepare("UPDATE produk SET nama=?, deskripsi=?, kategori=? WHERE id=?");
        $stmt->bind_param("ssi", $nama, $deskripsi, $kategori, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='product.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }

    $stmt->close();
}

if (isset($_SESSION['alert'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let alertData = "<?= $_SESSION['alert'] ?>".split("|");
            let alertType = alertData[0]; // success atau error
            let alertMessage = alertData[1];

            Swal.fire({
                icon: alertType,
                title: alertType === "success" ? "Berhasil" : "Gagal",
                text: alertMessage,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            });
        });
    </script>
    <?php unset($_SESSION['alert']); // Hapus session setelah ditampilkan ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Product | Casa Klamby </title>

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
    // Menampilkan SweetAlert jika ada status di URL
    if (isset($_GET['status'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {";
        
        if ($_GET['status'] == "product_success") {
            echo "Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Produk berhasil ditambahkan!',
                showConfirmButton: false,
                timer: 2000
            });";
        } elseif ($_GET['status'] == "product_error") {
            echo "Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal menambahkan produk!',
                showConfirmButton: true
            });";
        } elseif ($_GET['status'] == "upload_error") {
            echo "Swal.fire({
                icon: 'error',
                title: 'Upload Gagal!',
                text: 'Gagal mengunggah gambar!',
                showConfirmButton: true
            });";
        } elseif ($_GET['status'] == "format_error") {
            echo "Swal.fire({
                icon: 'warning',
                title: 'Format Tidak Didukung!',
                text: 'Gunakan gambar berformat JPG, PNG, atau GIF.',
                showConfirmButton: true
            });";
        } elseif ($_GET['status'] == "no_file") {
            echo "Swal.fire({
                icon: 'warning',
                title: 'Gambar Belum Dipilih!',
                text: 'Silakan unggah gambar produk.',
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
                class="sidebar-item active">
                <a href="product.php" class='sidebar-link'>
                    <i class="bi bi-collection-fill"></i>
                    <span>Product</span>
                </a>
            </li>
            <li
                class="sidebar-item ">
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
                <h3>Product</h3>
                <!-- Modal Tambah Produk -->
                <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addProductModalLabel">Tambah Produk</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="../config/tambah_product.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Produk</label>
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kategori" class="form-label">Kategori</label>
                                        <select class="form-control" id="kategori" name="kategori">
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            <option value="dad">Dad</option>
                                            <option value="mom">Mom</option>
                                            <option value="kid">Kid</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Gambar Produk</label>
                                        <input type="file" class="form-control" id="image" name="image" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Edit -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Mengubah Produk</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="../config/update_product.php" method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <input type="hidden" name="id" id="edit-id">

                                    <div class="mb-3">
                                        <label for="edit-nama" class="form-label">Nama</label>
                                        <input type="text" name="nama" id="edit-nama" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-deskripsi" class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" id="edit-deskripsi" class="form-control" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-kategori" class="form-label">Kategori</label>
                                        <select name="kategori" id="edit-kategori" class="form-control" required>
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            <option value="dad">Dad</option>
                                            <option value="mom">Mom</option>
                                            <option value="kid">Kid</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Masukkan Gambar</label>
                                        <input type="file" name="image" class="form-control">
                                        <p>Gambar saat ini:</p>
                                        <img id="edit-preview" src="" alt="Preview Image" width="100" class="mt-2">
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Basic Tables start -->
    <?php
        $query = "SELECT * FROM produk ORDER BY id DESC"; // Ambil data dari database
        $result = $conn->query($query);
        ?>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        Tambah Produk
                    </button>
                    <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Deskripsi</th>
                            <th>Kategori</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama']); ?></td>
                            <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                            <td><?= htmlspecialchars($row['kategori']); ?></td>
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <img src="../produk/<?= htmlspecialchars($row['image']); ?>" width="50" height="50">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-primary edit-button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-id="<?= $row['id']; ?>"
                                    data-nama="<?= htmlspecialchars($row['nama']); ?>"
                                    data-deskripsi="<?= htmlspecialchars($row['deskripsi']); ?>"
                                    data-kategori="<?= htmlspecialchars($row['kategori']); ?>"
                                    data-image="<?= htmlspecialchars($row['image']?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    Edit
                                </button>
                                <button class="btn btn-danger delete-button"
                                    data-id="<?= $row['id']; ?>"
                                    data-image="<?= htmlspecialchars($row['image'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Tables end -->
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
        document.addEventListener("DOMContentLoaded", function () {
            const editButtons = document.querySelectorAll(".edit-button");

            editButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const id = this.getAttribute("data-id");
                    const nama = this.getAttribute("data-nama");
                    const deskripsi = this.getAttribute("data-deskripsi");
                    const kategori = this.getAttribute("data-kategori");
                    const image = this.getAttribute("data-image");

                    document.getElementById("edit-id").value = id;
                    document.getElementById("edit-nama").value = nama;
                    document.getElementById("edit-deskripsi").value = deskripsi;
                    document.getElementById("edit-kategori").value = kategori;

                    if (image) {
                        document.getElementById("edit-preview").src = "../produk/" + image;
                    } else {
                        document.getElementById("edit-preview").src = "";
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-button").forEach(button => {
                button.addEventListener("click", function() {
                    const productId = this.getAttribute("data-id");
                    const imageName = this.getAttribute("data-image");

                    Swal.fire({
                        title: "Yakin ingin menghapus?",
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../config/delete_product.php?id=" + productId + "&image=" + imageName;
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>