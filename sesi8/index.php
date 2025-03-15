<?php
include 'koneksi.php';

// Ambil kategori jika ada filter
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$query = "SELECT * FROM produk";
if (!empty($filter_kategori)) {
    $query .= " WHERE kategori = '$filter_kategori'";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2 class="text-center my-4">Daftar Produk</h2>

    <!-- Filter Kategori -->
    <form method="GET" class="mb-3">
        <div class="input-group">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Pakaian">Pakaian</option>
                <option value="Makanan">Makanan</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <!-- Produk -->
    <div class="produk-container">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="card">
                <img src="uploads/<?php echo $row['gambar']; ?>" class="card-img-top" alt="<?php echo $row['nama']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['nama']; ?></h5>
                    <p class="card-text">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <a href="detail_produk.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>


</body>
</html>
