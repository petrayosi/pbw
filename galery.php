<?php
session_start();
include "koneksi.php";
include "upload_foto.php";

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

// Pagination settings
$items_per_page = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $items_per_page;

// Handle form submissions
if (isset($_POST['simpan'])) {
    $target_dir = "img/";
    $new_filename = basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . $new_filename;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check file type
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowed_types)) {
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO foto (foto) VALUES (?)");
            $stmt->bind_param("s", $new_filename);
            $stmt->execute();
            $_SESSION['message'] = "Foto berhasil ditambahkan!";
        } else {
            $_SESSION['message'] = "Gagal mengunggah foto.";
        }
    } else {
        $_SESSION['message'] = "Format file tidak didukung.";
    }
    header("Location: admin.php");
    exit();
}

// Handle image deletion
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $foto = $_POST['foto'];
    
    // First, get the image filename from database
    $stmt = $conn->prepare("SELECT foto FROM foto WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        // Delete from database
        $stmt = $conn->prepare("DELETE FROM foto WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // If database deletion successful, delete the file
            $file_path = "img/" . $row['foto'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            $_SESSION['message'] = "Foto berhasil dihapus!";
        } else {
            $_SESSION['message'] = "Gagal menghapus foto dari database.";
        }
    } else {
        $_SESSION['message'] = "Foto tidak ditemukan.";
    }
    
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CRUD galery | Admin</title>
    <link rel="icon" href="img/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem 0;
        }
        .gallery-item {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .gallery-item:hover img {
            transform: scale(1.05);
        }
        .gallery-actions {
            padding: 1rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm bg-body-tertiary sticky-top bg-danger-subtle">
        <div class="container">
            <a class="navbar-brand" href=".">My Daily Journal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="admin.php?page=dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin.php?page=article">Article</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-danger fw-bold" href="#" role="button" data-bs-toggle="dropdown">
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

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="display-6 mb-0">Galeri Foto</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-lg"></i> Tambah Foto
                    </button>
                </div>

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-info alert-dismissible fade show">
                        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="gallery-container">
                    <?php
                    $total_records = $conn->query("SELECT COUNT(*) as count FROM foto")->fetch_assoc()['count'];
                    $total_pages = ceil($total_records / $items_per_page);

                    $sql = "SELECT * FROM foto ORDER BY id DESC LIMIT ?, ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $start_from, $items_per_page);
                    $stmt->execute();
                    $hasil = $stmt->get_result();

                    while ($row = $hasil->fetch_assoc()) {
                        $image_path = $row['foto'] ? "img/" . $row['foto'] : "img/default.jpg";
                    ?>
                        <div class="gallery-item">
                            <img src="<?= $image_path ?>" alt="Foto">
                            <div class="gallery-actions">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id'] ?>">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                            
                            <!-- Modal Hapus -->
                            <div class="modal fade" id="modalHapus<?= $row['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <p>Apakah Anda yakin ingin menghapus foto ini?</p>
                                            <img src="<?= $image_path ?>" alt="Foto" class="img-fluid mb-3" style="max-height: 300px;">
                                            <form method="post">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="foto" value="<?= $row['foto'] ?>">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo;</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <label class="form-label">Foto:</label>
                        <input type="file" class="form-control" name="foto" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>