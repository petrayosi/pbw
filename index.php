<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Daily Journal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="icon" href="img/iconweb.jpeg" type="image/x-icon">
    <style>
        .hero {
            background: linear-gradient(135deg, #f8d7da, #fce4e7);
            padding: 80px 0;
        }
        .card {
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .schedule-card .card-header {
            padding: 1rem;
        }
        .profile-section {
            background: linear-gradient(135deg, #ee69c4, #f8d7da);
            padding: 40px 0;
            margin-top: 40px;
        }
        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .nav-item .nav-link {
            padding: 0.8rem 1.2rem;
            transition: color 0.3s ease;
        }
        .nav-item .nav-link:hover {
            color: #ee69c4;
        }
        .section-title {
            margin-bottom: 3rem;
            position: relative;
            padding-bottom: 1rem;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #ee69c4;
        }
         
        .footer {
            background: #343a40;
            color: white;
            padding: 40px 0;
        }
        .footer a {
            color: white;
            transition: opacity 0.3s ease;
        }
        .footer a:hover {
            opacity: 0.8;
        }
        .row.flex-row {
    gap: 1rem;
}

.row.flex-row.overflow-auto {
    overflow-x: auto;
    white-space: nowrap;
}

    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm bg-body-tertiary sticky-top">
      <div class="container">
        <a class="navbar-brand" href="#">My Daily Journal</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
            <li class="nav-item">
              <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#article">Article</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#gallery">Gallery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#jadwal">Schedule</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#profile">About Me</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link" href="admin.php?page=article">atricle</a>
            </li> -->
            <li class="nav-item">
              <a class="nav-link" href="admin.php?page=dashboard">dashboard</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Admin
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Foto Profil</a></li>
                  <li><a class="dropdown-item" href="admin.php?page=cruduser.php">Ganti Password</a></li>
                  <li><a class="dropdown-item" href="#">Logout</a></li>
                </ul>
              </li>
          </ul>
        </div>
      </div>
    </nav>

    <section class="hero" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-4">Create Memories, Save Memories, Everyday</h1>
                    <p class="lead">Mencatat semua kegiatan sehari-hari yang ada tanpa terkecuali</p>
                    <a href="#article" class="btn btn-primary btn-lg">Start Journaling</a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="img/gambar.jpeg" class="img-fluid rounded-3 shadow" alt="Hero Image">
                </div>
            </div>
        </div>
    </section>

    <section id="article" class="py-5">
    <div class="container">
        <h2 class="text-center section-title">Featured Articles</h2>
        <div class="row g-4">
            <?php
            include "koneksi.php"; // Koneksi ke database

            // Query untuk mengambil data artikel
            $sql = "SELECT * FROM article ORDER BY tanggal DESC";
            $hasil = $conn->query($sql);

            if ($hasil && $hasil->num_rows > 0) {
                // Looping untuk menampilkan data dalam bentuk card
                while ($row = $hasil->fetch_assoc()) {
                    // Pastikan path gambar benar
                    $image_path = "img/" . htmlspecialchars($row['gambar']);
            ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <!-- Gambar Artikel -->
                        <?php if (file_exists($image_path)) { ?>
                            <img src="<?= $image_path ?>" class="card-img-top img-fluid img-equal" alt="<?= htmlspecialchars($row['judul']) ?>">
                        <?php } else { ?>
                            <img src="img/default.jpg" class="card-img-top img-fluid img-equal" alt="Gambar Default">
                        <?php } ?>

                        <!-- Isi Card -->
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['judul']) ?></h5>
                            <p class="card-text">
                                <?= htmlspecialchars(substr($row['isi'], 0, 100)) . '...' ?>
                            </p>
                        </div>
                        <div class="card-footer">
                            <small class="text-body-secondary">
                                Last updated: <?= htmlspecialchars($row['tanggal']) ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php
                } // Akhir while
            } else {
                echo "<p class='text-danger'>Tidak ada artikel yang tersedia.</p>";
            }
            ?>
        </div>
    </div>
    <style>
    /* Menetapkan tinggi gambar yang konsisten */
    .img-equal {
        height: 200px;
        object-fit: cover; /* Memastikan gambar menutupi area dengan proporsi yang benar */
    }
</style>
</section>

    
<section id="gallery" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold position-relative d-inline-block">
                Gallery
                <span class="position-absolute w-50 h-2 bg-primary rounded bottom-0 start-50 translate-middle-x"></span>
            </h1>
        </div>
        
        <div id="carouselGallery" class="carousel slide carousel-fade shadow-lg rounded-4 overflow-hidden">
            <?php
            // Ambil data dari tabel `foto`
            $query = "SELECT * FROM foto ORDER BY id DESC";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0): 
            ?>
            <!-- Indicators -->
            <div class="carousel-indicators">
                <?php
                $slideCount = $result->num_rows;
                for ($i = 0; $i < $slideCount; $i++) {
                    echo '<button type="button" data-bs-target="#carouselGallery" data-bs-slide-to="' . $i . '"';
                    echo $i === 0 ? ' class="active"' : '';
                    echo '></button>';
                }
                ?>
            </div>

            <!-- Carousel Items -->
            <div class="carousel-inner">
                <?php
                $isFirst = true;
                while ($row = $result->fetch_assoc()) {
                    $image_path = "img/" . htmlspecialchars($row['foto']);
                ?>
                    <div class="carousel-item <?php echo $isFirst ? 'active' : ''; ?>">
                        <div class="position-relative">
                            <img src="<?php echo $image_path; ?>" 
                                 height="600px" 
                                 class="d-block w-100 object-fit-cover" 
                                 alt="Gallery Image">
                        </div>
                    </div>
                <?php
                    $isFirst = false;
                }
                ?>
            </div>

            <!-- Navigation Arrows -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselGallery" data-bs-slide="prev">
                <span class="carousel-control-prev-icon p-3 bg-dark bg-opacity-25 rounded-circle" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselGallery" data-bs-slide="next">
                <span class="carousel-control-next-icon p-3 bg-dark bg-opacity-25 rounded-circle" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .carousel-item {
            transition: transform 0.6s ease-in-out;
        }
        
        .carousel-item img {
            filter: brightness(0.9);
            transition: all 0.3s ease;
        }
        
        .carousel-item:hover img {
            filter: brightness(1);
            transform: scale(1.02);
        }
        
        .carousel-indicators {
            bottom: 30px;
        }
        
        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 5px;
        }
        
        .h-2 {
            height: 4px;
            bottom: -10px;
        }
    </style>
</section>

<section id="jadwal">
    </section>
    <section class="container my-5">
        <h1 class="fw-bold display-4 text-center">Jadwal Kuliah & Kegiatan Mahasiswa</h1>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <!-- Card Senin -->
            <div class="col schedule-colors">
                <div class="card h-100">
                    <div class="card-header senin">
                        <h5 class="card-title mb-0">Senin</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">11:00 - 12:30<br>Rekayasa Perangkat Lunak<br>Ruang H.3.4</p>
                        <p class="card-text">13:00 - 15:00<br>Dasar Pemrograman<br>Ruang H.3.1</p>
                    </div>
                </div>
            </div>
            <!-- Card Selasa -->
            <div class="col">
                <div class="card h-100">
                    <div class="card-header selasa">
                        <h5 class="card-title mb-0">Selasa</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">12:30 - 14:00<br>Pemrograman Berbasis Web<br>Ruang D.2.J</p>
                        <p class="card-text">14:00 - 16:00<br>Penambangan Data<br>Ruang D.3.M</p>
                    </div>
                </div>
            </div>
            <!-- Card Rabu -->
            <div class="col">
                <div class="card h-100">
                    <div class="card-header rabu">
                        <h5 class="card-title mb-0">Rabu</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">10:00 - 12:00<br>Pemrograman Berbasis Objek<br>Ruang H.4.11</p>
                        <p class="card-text">13:00 - 15:00<br>Pemrograman Sisi Klen<br>Ruang H.3.11</p>
                    </div>
                </div>
            </div>
            <!-- Card Kamis -->
            <div class="col">
                <div class="card h-100">
                    <div class="card-header kamis">
                        <h5 class="card-title mb-0">Kamis</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">08:00 - 10:00<br>Pengantar Teknologi Informasi<br>>Ruang H.4.11/p>
                        <p class="card-text">11:00 - 13:00<br>siste Informasi<br>>Ruang H.4.6</p>
                    </div>
                </div>
            </div>
            <!-- Card Jumat -->
            <div class="col">
                <div class="card h-100">
                    <div class="card-header jumat">
                        <h5 class="card-title mb-0">Jumat</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">08:00 - 10:00<br>Bengkel Koding <br>>Ruang H.4.11/p>
                        <p class="card-text">11:00 - 13:00<br>siste Terkini<br>>Ruang H.4.6</p>
                    </div>
                </div>
            </div>
            <!-- Card Sabtu -->
            <div class="col">
                <div class="card h-100">
                    <div class="card-header sabtu">
                        <h5 class="card-title mb-0">Sabtu</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">08:00 - 10:00<br>Bimbingan Karir Online</p>
                        <p class="card-text">10:30 - 12:00<br>Bimbingan sikis</p>
                    </div>
                </div>
            </div>
            <!-- Card Minggu -->
            <div class="col">
                <div class="card h-100">
                    <div class="card-header minggu">
                        <h5 class="card-title mb-0">Minggu</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">HURA-HURA</p>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .hero {
                background-color: #f8d7da !important; /* Warna pink lembut */
                padding: 40px 0 !important;
            }
            /* Warna untuk setiap hari */
            .senin { background-color: #ee0404 !important; color: #fff !important; }
            .selasa { background-color: #b9e313 !important; color: #fff !important; }
            .rabu { background-color: #dc3545 !important; color: #fff !important; }
            .kamis { background-color: #2307dd !important; color: #fff !important; }
            .jumat { background-color: #00ff37 !important; color: #fff !important; }
            .sabtu { background-color: #6c757d !important; color: #fff !important; }
            .minggu { background-color: #fb09cb !important; color: #fff !important; }
        </style>
    </section>

    <section id="profile" class="profile-section">
        <div class="container">
            <div class="profile-card mx-auto" style="max-width: 700px;">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <img src="img/gambar.jpeg" class="rounded-circle img-fluid mb-3" alt="Profile">
                        <h3 class="mb-2">Fufufafa</h3>
                        <p class="text-muted">Wakil Hokage Konohagakure</p>
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tr>
                                <td><strong>NIM</strong></td>
                                <td>A11.1990.13085</td>
                            </tr>
                            <tr>
                                <td><strong>Program Studi</strong></td>
                                <td>Bisnis F&B</td>
                            </tr>
                            <!-- More profile details... -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer text-center">
        <div class="container">
            <div class="mb-4">
                <a href="#" class="mx-2"><i class="bi bi-instagram h3"></i></a>
                <a href="#" class="mx-2"><i class="bi bi-twitter h3"></i></a>
                <a href="#" class="mx-2"><i class="bi bi-whatsapp h3"></i></a>
            </div>
            <p>&copy; 2024 SISTEM KEBUT 1 MALAM</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>