<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tambah Produk - Sesi 7</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">
    <h2>Tambah Produk</h2>
    <form method="POST" action="">
        <label>Nama Produk:</label>
        <input type="text" name="nama" placeholder="Masukkan nama produk">
        
        <label>Harga:</label>
        <input type="number" name="harga" placeholder="Masukkan harga produk">
        
        <label>Deskripsi:</label>
        <textarea name="deskripsi" placeholder="Masukkan deskripsi produk"></textarea>
        
        <button type="submit" class="btn" name="submit">Tambah Produk</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = $_POST["nama"];
        $harga = $_POST["harga"];
        $deskripsi = $_POST["deskripsi"];

        // Validasi: Pastikan input tidak kosong
        if (empty($nama) || empty($harga) || empty($deskripsi)) {
            echo "<p class='error'>Semua field harus diisi!</p>";
        } else {
            // Proses data (misalnya, simpan ke database)
            echo "<p class='success'>Produk berhasil ditambahkan!</p>";
            echo "<p><strong>Nama:</strong> $nama</p>";
            echo "<p><strong>Harga:</strong> Rp$harga</p>";
            echo "<p><strong>Deskripsi:</strong> $deskripsi</p>";
        }
    }
    ?>
</div>

</body>
</html>
