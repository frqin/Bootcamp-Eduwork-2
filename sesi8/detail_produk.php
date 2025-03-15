<?php
include 'koneksi.php';

// Periksa apakah id ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID produk tidak ditemukan.");
}

$id = intval($_GET['id']); // Pastikan ID adalah angka

$query = "SELECT * FROM produk WHERE id = $id";
$result = mysqli_query($conn, $query);

// Periksa apakah produk ditemukan
if (!$result || mysqli_num_rows($result) == 0) {
    die("Produk tidak ditemukan.");
}

$row = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2><?php echo $row['nama']; ?></h2>
    <img src="uploads/<?php echo $row['gambar']; ?>" width="300"><br>
    <p><strong>Harga:</strong> Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
    <p><strong>Deskripsi:</strong> <?php echo $row['deskripsi']; ?></p>
    <p><strong>Kategori:</strong> <?php echo $row['kategori']; ?></p>
    <p><strong>Stok:</strong> <?php echo $row['stok']; ?> unit</p>
</div>

</body>
</html>
