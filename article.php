<?php
include "koneksi.php";
include "upload_foto.php"; // Untuk upload gambar

// Tambah/Edit Data
if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = date("Y-m-d H:i:s");
    $username = $_SESSION['username'];
    $gambar = '';

    if (!empty($_FILES['gambar']['name'])) {
        $upload = upload_foto($_FILES['gambar']);
        if ($upload['status']) {
            $gambar = $upload['message'];
        } else {
            echo "<script>alert('".$upload['message']."');</script>";
            exit;
        }
    } else {
        $gambar = $_POST['gambar_lama'] ?? '';
    }

    if (isset($_POST['id'])) {
        // Update Data
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE article SET judul=?, isi=?, gambar=?, tanggal=?, username=? WHERE id=?");
        $stmt->bind_param("sssssi", $judul, $isi, $gambar, $tanggal, $username, $id);
    } else {
        // Tambah Data
        $stmt = $conn->prepare("INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $judul, $isi, $gambar, $tanggal, $username);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan'); document.location='admin.php?page=article';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data');</script>";
    }
}

// Hapus Data
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    if ($gambar && file_exists("img/$gambar")) {
        unlink("img/$gambar");
    }

    $stmt = $conn->prepare("DELETE FROM article WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus'); document.location='admin.php?page=article';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
}
?>

<div class="container">
    <div class="row">
        <h4 class="lead display-6 pb-2 border-bottom border-danger-subtle">Daftar Artikel</h4>
        <!-- Button Tambah Data -->
        <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah Article
        </button>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Isi</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM article ORDER BY tanggal DESC";
                    $hasil = $conn->query($sql);
                    $no = 1;
                    while ($row = $hasil->fetch_assoc()) {
                        $image_path = $row['gambar'] ? "img/".$row['gambar'] : "img/default.jpg";
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['judul']) ?></td>
                            <td><?= htmlspecialchars(substr($row['isi'], 0, 50)) ?>...</td>
                            <td><img src="<?= $image_path ?>" width="100" alt="Gambar"></td>
                            <td>
                                <a href="#" class="badge rounded-pill text-bg-success" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>"><i class="bi bi-pencil"></i></a>
                                <a href="#" class="badge rounded-pill text-bg-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id'] ?>"><i class="bi bi-x-circle"></i></a>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Edit Artikel</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="gambar_lama" value="<?= $row['gambar'] ?>">
                                            <label>Judul:</label>
                                            <input type="text" class="form-control" name="judul" value="<?= $row['judul'] ?>" required>
                                            <label>Isi:</label>
                                            <textarea class="form-control" name="isi"><?= $row['isi'] ?></textarea>
                                            <label>Ganti Gambar:</label>
                                            <input type="file" class="form-control" name="gambar">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Hapus -->
                        <div class="modal fade" id="modalHapus<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="post">
                                        <div class="modal-body">
                                            <p>Yakin ingin menghapus artikel "<strong><?= $row['judul'] ?></strong>"?</p>
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="gambar" value="<?= $row['gambar'] ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Tambah Artikel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <label>Judul:</label>
                    <input type="text" class="form-control" name="judul" required>
                    <label>Isi:</label>
                    <textarea class="form-control" name="isi" required></textarea>
                    <label>Gambar:</label>
                    <input type="file" class="form-control" name="gambar">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
