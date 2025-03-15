<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];

    // Handle Upload Gambar
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp_name, "uploads/" . $gambar);

    // Simpan ke Database
    $query = "INSERT INTO produk (nama, deskripsi, harga, stok, kategori, gambar) VALUES ('$nama', '$deskripsi', '$harga', '$stok', '$kategori', '$gambar')";
    mysqli_query($conn, $query);

    echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="container">
        <h2 class="text-center mb-4">Tambah Produk</h2>
        <div class="card shadow p-4">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" class="form-control" name="harga" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" class="form-control" name="stok" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="kategori">
                        <option value="Elektronik">Elektronik</option>
                        <option value="Pakaian">Pakaian</option>
                        <option value="Makanan">Makanan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Gambar</label>
                    <input type="file" class="form-control" name="gambar" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Tambah Produk</button>
            </form>
        </div>
    </div>
</body>

</body>
</html>
