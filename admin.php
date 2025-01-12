<?php
session_start();
include "koneksi.php";  

// Cek jika belum ada user yang login, arahkan ke halaman login
if (!isset($_SESSION['username'])) { 
    header("location:login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Daily Journal | Admin</title>
    <link rel="icon" href="img/logo.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="icon" href="img/iconweb.jpeg" type="image/x-icon">
    <style>
        html { position: relative; min-height: 100%; }
        body { margin-bottom: 100px; }
        footer { position: absolute; bottom: 0; width: 100%; height: 100px; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm bg-body-tertiary sticky-top bg-danger-subtle">
        <div class="container">
            <a class="navbar-brand" href=".">My Daily Journal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
                    <li class="nav-item"><a class="nav-link" href="admin.php?page=dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin.php?page=article">Article</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-danger fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $_SESSION['username'] ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section id="content" class="p-5">
        <div class="container">
            <?php
            // Cek parameter page
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                $allowed_pages = ['dashboard', 'article']; // Daftar file yang diizinkan
                if (in_array($page, $allowed_pages)) {
                    echo "<h4 class='lead display-6 pb-2 border-bottom border-danger-subtle'>" . ucfirst($page) . "</h4>";
                    include($page . ".php");
                } else {
                    echo "<h4 class='text-danger'>Halaman tidak ditemukan!</h4>";
                }
            } else {
                echo "<h4 class='lead display-6 pb-2 border-bottom border-danger-subtle'>Dashboard</h4>";
                include("dashboard.php");
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center p-5 bg-danger-subtle">
        <div>
            <a href="https://www.instagram.com/udinusofficial"><i class="bi bi-instagram h2 p-2 text-dark"></i></a>
            <a href="https://twitter.com/udinusofficial"><i class="bi bi-twitter h2 p-2 text-dark"></i></a>
            <a href="https://wa.me/+62812685577"><i class="bi bi-whatsapp h2 p-2 text-dark"></i></a>
        </div>
        <div>Fufufafa &copy; 2025</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
