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
            <div class="main-body">

                  <div class="row gutters-sm">
                    <div class="col-md-4 mb-3">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex flex-column align-items-center text-center">
                            <img src="https://cdn.idntimes.com/content-images/duniaku/post/20200820/killua-zoldyck-electric-3c4679201e4b56356cf16280f9f8cc44.jpg" alt="Killua Zoldyck" class="rounded-circle" width="150" alt="Admin" class="rounded-circle" width="150">
                            <div class="mt-3">
                              <h4>Killua Zoldyck</h4>
                              <p class="text-secondary mb-1">Hunter & Assassin</p>
                              <p class="text-muted font-size-sm">Kukuroo Mountain, Zoldyck Family Estate</p>
                              <button class="btn btn-primary">Follow</button>
                              <button class="btn btn-outline-primary">Message</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card mt-3">
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe mr-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>Website</h6>
                            <span class="text-secondary">https://hunterxhunter.com</span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github mr-2 icon-inline"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>Github</h6>
                            <span class="text-secondary">"https://github.com/petrayosi"</span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter mr-2 icon-inline text-info"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>Twitter</h6>
                            <span class="text-secondary">@Moga_Aja</span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram mr-2 icon-inline text-danger"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>Instagram</h6>
                            <span class="text-secondary">@nilai_AB</span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook mr-2 icon-inline text-primary"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>Facebook</h6>
                            <span class="text-secondary">@HEHEHEH</span>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="card mb-3">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-3">
                              <h6 class="mb-0">Full Name</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                Killua Zoldyck
                            </div>
                          </div>
                          <hr>
                          <div class="row">
                            <div class="col-sm-3">
                              <h6 class="mb-0">Email</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                              mogaGakNgulang@gmail.com
                            </div>
                          </div>
                          <hr>
                          <div class="row">
                            <div class="col-sm-3">
                              <h6 class="mb-0">Phone</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                              087820434943
                            </div>
                          </div>
                          <hr>
                          <div class="row">
                            <div class="col-sm-3">
                              <h6 class="mb-0">Mobile</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                              911
                            </div>
                          </div>
                          <hr>
                          <div class="row">
                            <div class="col-sm-3">
                              <h6 class="mb-0">Address</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                Kukuroo Mountain, Zoldyck Family Estate
                            </div>
                          </div>
                          <hr>
                          <div class="row">
                            <div class="col-sm-12">
                            
                            </div>
                          </div>
                        </div>
                      </div>
        
                      <div class="row gutters-sm">
                        <!-- Killua's Stats -->
                        <div class="col-sm-6 mb-3">
                          <div class="card h-100">
                            <div class="card-body">
                              <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">bolt</i>Killua's Stats</h6>
                              <small>Speed</small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <small>Strength</small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <small>Intelligence</small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <small>Nen Mastery</small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <small>Stealth</small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 92%" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      
                        <!-- Project Status -->
                        <div class="col-sm-6 mb-3">
                          <div class="card h-100">
                            <div class="card-body">
                              <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Petra's ability</i>ability</h6>
                              <small>Web Design</small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 2%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <small>Website Markup</small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 12%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <small>One Page</small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 20%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <small>Mobile Template</small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 45%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <small>Ngentekke Sego</small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 99%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      
        
        
        
                    </div>
                  </div>
        
                </div>
            </div>
    <footer class="footer text-center">
        <div class="container">
            <div class="mb-4">
                <a href="#" class="mx-2"><i class="bi bi-instagram h3"></i></a>
                <a href="#" class="mx-2"><i class="bi bi-twitter h3"></i></a>
                <a href="#" class="mx-2"><i class="bi bi-whatsapp h3"></i></a>
            </div>
            <p>&copy; 2025 SISTEM KEBUT 1 MALAM</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>