<?php
// Query untuk mengambil data artikel
$sql1 = "SELECT * FROM article ORDER BY tanggal DESC";
$hasil1 = $conn->query($sql1);

// Menghitung jumlah baris data artikel
$jumlah_article = $hasil1->num_rows;

$sql2 = "SELECT * FROM user";
$hasil2 = $conn->query($sql2);

// Menghitung jumlah baris data user
$jumlah_user = $hasil2->num_rows;


$sql3 = "SELECT * FROM foto";
$hasil3 = $conn->query($sql3);

// Menghitung jumlah baris data user
$jumlah_foto = $hasil3->num_rows;
?>

<div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center pt-4">
    <!-- Card Article -->
    <div class="col">
        <div class="card border border-danger mb-3 shadow" style="max-width: 18rem;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="p-3">
                        <h5 class="card-title"><i class="bi bi-newspaper"></i> Article</h5>
                    </div>
                    <div class="p-3">
                        <span class="badge rounded-pill text-bg-danger fs-2"><?php echo $jumlah_article; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Gallery -->
    <div class="col">
    <a href="galery.php" class="text-decoration-none">
        <div class="card border border-danger mb-3 shadow" style="max-width: 18rem;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="p-3">
                        <h5 class="card-title"><i class="bi bi-images"></i> Gallery</h5>
                    </div>
                    <div class="p-3">
                        <span class="badge rounded-pill text-bg-danger fs-2"><?php echo $jumlah_foto; ?></span>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>

    <!-- Card User, yang mengarah ke CRUD User -->
    <div class="col">
        <a href="cruduser.php" class="text-decoration-none">
            <div class="card border border-danger mb-3 shadow" style="max-width: 18rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="p-3">
                            <h5 class="card-title"><i class="bi bi-person"></i> User</h5>
                        </div>
                        <div class="p-3">
                            <span class="badge rounded-pill text-bg-danger fs-2"><?php echo $jumlah_user; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
