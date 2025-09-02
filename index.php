<?php
require "config/conn.php";
// Ambil data dari database
$result = $conn->query("SELECT text1, text2, text3 FROM profile_text WHERE id=1");

if ($result && $result->num_rows > 0) {
    $cor = $result->fetch_assoc();
} else {
    // Jika data tidak ditemukan, buat nilai default agar tidak error
    $cor = ["text1" => "Default Text 1", "text2" => "Default Text 2", "text3" => "Default Text 3"];
}
// ID gambar yang ingin diambil
$ids = [2, 3]; // Bisa disesuaikan

// Buat query dengan placeholder
//$placeholders = implode(',', array_fill(0, count($ids), '?')); // Buat ?,? sesuai jumlah ID
//$query = "SELECT id, image FROM about WHERE id IN ($placeholders)";

//$stmt = $conn->prepare($query);

// Bind parameter secara dinamis
//$typeStr = str_repeat('i', count($ids)); // Buat string "ii" untuk 2 integer
//$stmt->bind_param($typeStr, ...$ids);
//$stmt->execute();
//$about = $stmt->get_result();

// Simpan hasil ke dalam array
//$images = [];
//while ($row = $about->fetch_assoc()) {
//    $images[$row['id']] = $row['image'];
//}

// Ambil data visi (hanya satu entri)
$resultVisi = $conn->query("SELECT visi FROM visi LIMIT 1");
$visi = $resultVisi->num_rows > 0 ? $resultVisi->fetch_assoc()['visi'] : "Visi belum dibuat";

// Ambil data misi (bisa lebih dari satu)
$resultMisi = $conn->query("SELECT misi FROM misi");
$misiList = [];
while ($row = $resultMisi->fetch_assoc()) {
    $misiList[] = $row['misi'];
}

// Ambil data produk dari database
$resultProduk = $conn->query("SELECT * FROM produk");

$produkList = [];
while ($row = $resultProduk->fetch_assoc()) {
    $produkList[] = $row;
}

// Ambil FAQ database
$resultFAQ = $conn->query("SELECT * FROM faq");
$FAQList = [];
while ($row = $resultFAQ->fetch_assoc()){
  $FAQList[] = $row;
}

// Ambil Contact database
$resultContact = $conn->query("SELECT * FROM contact");
$contactList = [];
while ($row = $resultContact->fetch_assoc()){
  $contactList[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Casa Klamby Official</title>
  <link rel="shortcut icon" href="assets/compiled/png/image.png" type="image/x-icon">
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <style>
    .logo-container {
      width: 100%; /* Sesuaikan dengan ukuran gambar */
      margin: auto;
      overflow: hidden;
      border-radius: 8px; /* Opsional: memberi sudut membulat */
      display: inline-block;
    }

    .logo-container img {
      width: 100%; /* Pastikan gambar memenuhi frame */
      transition: transform 0.3s ease-in-out;
    }

    .logo-container:hover img {
      transform: scale(1.3); /* Gambar membesar saat hover */
    }
  </style>

  <!-- =======================================================
  * Template Name: Selecao
  * Template URL: https://bootstrapmade.com/selecao-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="sitename">Casa Klamby</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#product">Product</a></li>
          <li class="dropdown"><a href="javascript:void(0);"><span>Social Media</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <?php foreach ($contactList as $contact) { ?>
              <li>
                <a href="<?= htmlspecialchars($contact['laman']) ?>" target="_blank">
                  <?= htmlspecialchars($contact['nama']) ?>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <div id="hero-carousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">

        <!-- Slide 1 -->
        <div class="carousel-item active">
          <div class="carousel-container">
            <h2 class="animate__animated animate__fadeInDown">Welcome to <span>Casa Klamby</span></h2>
            <p class="animate__animated animate__fadeInUp"><?php echo $cor['text1']; ?></p>
            <a href="#about" class="btn-get-started animate__animated animate__fadeInUp scrollto">Tentang Kami</a>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
          <div class="carousel-container">
            <h2 class="animate__animated animate__fadeInDown">Produk Kami</h2>
            <p class="animate__animated animate__fadeInUp"><?php echo $cor['text2']; ?></p>
            <a href="#product" class="btn-get-started animate__animated animate__fadeInUp scrollto">Lihat Produk</a>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
          <div class="carousel-container">
            <h2 class="animate__animated animate__fadeInDown">Informasi</h2>
            <p class="animate__animated animate__fadeInUp"><?php echo $cor['text3']; ?></p>
            <a href="#contact" class="btn-get-started animate__animated animate__fadeInUp scrollto">Hubungi Kami</a>
          </div>
        </div>

        <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>

      </div>

      <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 " preserveAspectRatio="none">
        <defs>
          <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
        </defs>
        <g class="wave1">
          <use xlink:href="#wave-path" x="50" y="3"></use>
        </g>
        <g class="wave2">
          <use xlink:href="#wave-path" x="50" y="0"></use>
        </g>
        <g class="wave3">
          <use xlink:href="#wave-path" x="50" y="9"></use>
        </g>
      </svg>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>About</h2>
        <p>Visi Misi Kami</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-12 text-center" data-aos="fade-up" data-aos-delay="150">
              <div class="logo-container">              
                <img src="assets/compiled/png/image.png" class="img-fluid rounded shadow-sm mt-3" style="width: 25%;" alt="Logo Casa">
              </div>
            </div>
            <h4 class="mt-3 fw-bold">Visi</h4>
            <p class="text-break"><?php echo htmlspecialchars($visi); ?></p>    
          </div>
        <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
          <h4 class="fw-bold">Misi</h4>
          <ul>
            <?php foreach ($misiList as $misi): ?>
              <li><i class="bi bi-check2-circle"></i> <span><?php echo htmlspecialchars($misi); ?></span></li>
            <?php endforeach; ?>
          </ul>
        </div>
          
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section dark-background">

      <div class="container">

        <div class="row" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-xl-9 text-center text-xl-start">
            <h3>Haii Sahabat Casa...</h3>
            <p>Hubungi kami untuk informasi lebih lanjut! Tim kami siap membantu Anda. Silakan menghubungi kami melalui WhatsApp, email, atau media sosial untuk pertanyaan seputar produk, pemesanan, atau kerja sama.</p>
          </div>
          <div class="col-xl-3 cta-btn-container text-center">
            <a class="cta-btn align-middle" href="#contact">Hubungi</a>
          </div>
        </div>

      </div>

    </section><!-- /Call To Action Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Services</h2>
        <p>Apa yang bisa kami lakukan</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item  position-relative">
              <div class="icon">
                <i class="bi bi-cash-stack" style="color: #0dcaf0;"></i>
              </div>
                <h3>"Kemudahan Transaksi"</h3>
              <p>Nikmati pengalaman belanja yang mudah dengan berbagai metode pembayaran aman dan praktis</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-calendar4-week" style="color: #fd7e14;"></i>
              </div>
                <h3>"Pesan Sesuai Jadwal"</h3>
              <p>Atur pesanan sesuai kebutuhan Anda, dengan layanan pre-order dan pengiriman tepat waktu.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-chat-text" style="color: #20c997;"></i>
              </div>
                <h3>"Layanan Pelanggan"</h3>
              <p>Kami siap membantu Anda kapan saja dengan layanan pelanggan yang responsif dan profesional.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-credit-card-2-front" style="color: #df1529;"></i>
              </div>
                <h3>"Pengiriman Cepat & Aman"</h3>
              <p>Produk Anda dikirim dengan aman dan cepat ke seluruh Indonesia dengan partner logistik terpercaya.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-globe" style="color: #6610f2;"></i>
              </div>
                <h3>"Jangkauan Global"</h3>
              <p>Tersedia untuk pelanggan di seluruh dunia, menghadirkan fashion Muslim modern dengan kualitas terbaik.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-clock" style="color: #f3268c;"></i>
              </div>
                <h3>"Tepat Waktu & Efisien"</h3>
              <p>Proses produksi dan pengiriman kami memastikan pesanan Anda tiba sesuai jadwal.</p>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Product Section -->
    <section id="product" class="portfolio section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Product</h2>
        <p>Produk Kami</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

          <!-- Product Filters -->
          <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
            <li data-filter="*" class="filter-active">All</li>
            <li data-filter=".filter-dad">Dad</li>
            <li data-filter=".filter-mom">Mom</li>
            <li data-filter=".filter-kid">Kid</li>
          </ul><!-- End Product Filters -->

          <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
            <?php if (!empty($produkList)) : ?>
              <?php foreach ($produkList as $produk) : ?>
                <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-<?= htmlspecialchars($produk['kategori']); ?>">
                  <img src="produk/<?= htmlspecialchars($produk['image']); ?>" class="img-fluid" alt="<?= htmlspecialchars($produk['nama']); ?>">
                  <div class="portfolio-info">
                    <h4><?= htmlspecialchars($produk['nama']); ?></h4>
                    <p><?= htmlspecialchars($produk['deskripsi']); ?></p>
                    <a href="produk/<?= htmlspecialchars($produk['image']); ?>" title="<?= htmlspecialchars($produk['nama']); ?>" data-gallery="portfolio-gallery-<?php echo $row['kategori']; ?>" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else : ?>
              <p class="text-center">Tidak ada produk yang tersedia.</p>
            <?php endif; ?>
          </div><!-- End Product Container -->

        </div>

      </div>

    </section><!-- /Product Section -->

    <section id="call-to-action" class="call-to-action section dark-background">

      <div class="container">

        <div class="row" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-xl-9 text-center text-xl-start">
            <h3>Stay Modest, Look Fabulous with Casa Klamby</h3>
            <p>Waktunya tampil effortless chic dengan busana Muslim kekinian yang nyaman dipakai, elegan dipandang, dan pastinya bikin kamu stand out di tiap momen spesial. Koleksi terbaik kami siap lengkapi gaya kamu yang classy tapi tetap syar’i!</p>
          </div>
          <div class="col-xl-3 cta-btn-container text-center">
            <a class="cta-btn align-middle" href="#">Temukan!</a>
          </div>
        </div>

      </div>

    </section>
    <!-- Faq Section -->
    <section id="faq" class="faq section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Frequently Asked Questions</h2>
        <p>Pertanyaan</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up">
        <div class="row">
            <div class="col-12">
                <div class="custom-accordion" id="accordion-faq">
                    <?php foreach ($FAQList as $index => $faq) : ?>
                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link <?= $index === 0 ? '' : 'collapsed' ?>" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse-faq-<?= $faq['id'] ?>">
                                    <?= htmlspecialchars($faq['tanya']) ?>
                                </button>
                            </h2>

                            <div id="collapse-faq-<?= $faq['id'] ?>" 
                                class="collapse <?= $index === 0 ? 'show' : '' ?>" 
                                aria-labelledby="heading-<?= $faq['id'] ?>" 
                                data-parent="#accordion-faq">
                                <div class="accordion-body">
                                    <?= nl2br(htmlspecialchars($faq['jawab'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    </section><!-- /Faq Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section py-5 bg-light">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Customer Service</h2>
        <p>Hubungi Kami</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="row align-items-center gy-4">

          <!-- Bagian Kiri: Info Kontak -->
          <div class="col-lg-4">
            <div class="info-item d-flex mb-3" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h5 class="fw-bold">Alamat</h5>
                <p>Wonorejo Rt. 7 Rw. 3, Karanganyar, Kedung Banteng, Wonorejo, Kec. Karanganyar, Kabupaten Demak, Jawa Tengah 59582</p>
              </div>
            </div>

            <div class="info-item d-flex mb-3" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h5 class="fw-bold">Call Us</h5>
                <p>+62 89-3201-17913</p>
              </div>
            </div>

            <div class="info-item d-flex mb-3" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h5 class="fw-bold">Email Us</h5>
                <p>casaklamby@gmail.com</p>
              </div>
            </div>
          </div><!-- End Info Kontak -->

          <!-- Bagian Tengah: Gambar -->
          <div class="col-lg-4 text-center" data-aos="fade-up" data-aos-delay="500">
            <img src="assets/compiled/png/image.png" class="rounded shadow-sm" style="width: 70%;" alt="Logo Casa">
          </div>

          <!-- Bagian Kanan: Teks -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="600">
            <p class="text-muted"><?php echo $cor['text3']; ?></p>
          </div>

        </div>
      </div>
    </section>
  </main>

  <footer id="footer" class="footer dark-background">
    <div class="container">
      <h3 class="sitename">Casa Klamby</h3>
      <p><?php echo $cor['text1']; ?></p>
      <div class="social-links d-flex justify-content-center gap-3">
          <?php 
          $validIcons = ['whatsapp', 'tiktok', 'instagram', 'facebook', 'twitter']; // Daftar ikon Bootstrap yang tersedia

          foreach ($contactList as $contact) : 
              $nama = strtolower($contact['nama']); // Ubah ke lowercase agar konsisten
              $icon = in_array($nama, $validIcons) ? "bi bi-$nama" : "bi bi-bag-fill"; // Jika tidak ada, pakai bag-fill
          ?>
              <a href="<?= htmlspecialchars($contact['laman']) ?>" target="_blank">
                  <i class="<?= $icon; ?>"></i>
              </a>
          <?php endforeach; ?>
      </div>


      <!--<div class="social-links d-flex justify-content-center">
        <a href=""><i class="bi bi-whatsapp"></i></a>
        <a href=""><i class="bi bi-tiktok"></i></a>
        <a href=""><i class="bi bi-instagram"></i></a>
        <a href=""><i class="bi bi-bag-fill"></i></a> strtolower
      </div>-->
      <div class="container">
        <div class="copyright">
          <span>Copyright</span> <strong class="px-1 sitename">Casa Klamby</strong> <span>All Rights Reserved</span>
        </div>
        <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you've purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        </div>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>