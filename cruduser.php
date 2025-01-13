<?php
session_start();
include "koneksi.php";  

// Cek jika belum ada user yang login, arahkan ke halaman login
if (!isset($_SESSION['username'])) { 
    header("location:login.php"); 
    exit();
}
// Create User
if (isset($_POST['create_user'])) {
    $username = trim($_POST['username']);
    
    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username already exists";
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $foto = $_FILES['foto']['name'];
        
        // Validate image
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($foto);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO user (username, password, foto) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $password, $foto);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = "User successfully created";
                } else {
                    $_SESSION['error'] = "Error creating user: " . $conn->error;
                }
                $stmt->close();
            } else {
                $_SESSION['error'] = "Failed to upload image";
            }
        } else {
            $_SESSION['error'] = "Only image files are allowed";
        }
    }
    $check_stmt->close();
}

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $username = trim($_POST['username']);
    
    // Ambil data lama user
    $stmt = $conn->prepare("SELECT foto FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $foto = $user['foto'];
    
    // Cek apakah ada file baru untuk foto
    if (!empty($_FILES['foto']['name'])) {
        $new_foto = $_FILES['foto']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($new_foto);
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $foto = $new_foto;
        }
    }
    
    // Update user
    if (!empty($_POST['password'])) {
        // Jika password baru disediakan
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE user SET username = ?, password = ?, foto = ? WHERE id = ?");
        $update_stmt->bind_param("sssi", $username, $password, $foto, $id);
    } else {
        // Jika password tidak diubah
        $update_stmt = $conn->prepare("UPDATE user SET username = ?, foto = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $username, $foto, $id);
    }

    if ($update_stmt->execute()) {
        $_SESSION['success'] = "User berhasil diupdate.";
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat mengupdate user.";
    }
    $update_stmt->close();
}

// DELETE: Menghapus data user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM user WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "User berhasil dihapus.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$items_per_page = 4;
$current_page = isset($_GET['page_number']) ? $_GET['page_number'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Mengambil total jumlah data
$total_records = $conn->query("SELECT COUNT(*) as count FROM user")->fetch_assoc()['count'];
$total_pages = ceil($total_records / $items_per_page);

// Modifikasi query SELECT dengan LIMIT dan OFFSET
$sql = "SELECT * FROM user LIMIT $items_per_page OFFSET $offset";
$hasil = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CRUD User | Admin</title>
    <link rel="icon" href="img/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="icon" href="img/iconweb.jpeg" type="image/x-icon">
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

    <div class="container mt-5">
        <h3>Manage Users</h3>

        <!-- Form untuk tambah user baru -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" class="form-control" name="foto" required>
            </div>
            <button type="submit" name="create_user" class="btn btn-primary">Create User</button>
        </form>

        <hr>

        <!-- Tabel data user -->
        <h4 class="mt-4">User List</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Foto</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $hasil->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><img src="uploads/<?php echo $user['foto']; ?>" width="50" alt="foto"></td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $user['id']; ?>">Edit</button>
                            <!-- Tombol Hapus -->
                            <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Delete</a>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <!-- Modal Edit -->
<div class="modal fade" id="editModal<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (Kosongkan jika tidak ingin diubah)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" name="foto">
                    </div>
                    <button type="submit" name="update_user" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

                <?php } ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <!-- Previous button -->
                <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page_number=<?php echo $current_page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Page numbers -->
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page_number=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next button -->
                <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page_number=<?php echo $current_page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
