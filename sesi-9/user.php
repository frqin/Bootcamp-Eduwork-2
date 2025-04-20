<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "ecommerce_db";

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Inisialisasi keranjang
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Fungsi untuk menambahkan produk ke keranjang
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    // Cek stok produk
    $query = "SELECT * FROM produk WHERE id = $product_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    if ($product && $product['stok'] >= $quantity) {
        // Cek apakah produk sudah ada di keranjang
        $product_exists = false;
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                $_SESSION['cart'][$key]['quantity'] += $quantity;
                $product_exists = true;
                break;
            }
        }
        
        if (!$product_exists) {
            $_SESSION['cart'][] = array(
                'id' => $product_id,
                'nama' => $product['nama'],
                'harga' => $product['harga'],
                'quantity' => $quantity
            );
        }
        
        $success_message = "Produk berhasil ditambahkan ke keranjang!";
    } else {
        $error_message = "Stok produk tidak mencukupi!";
    }
}

// Hapus item dari keranjang
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
        $success_message = "Produk berhasil dihapus dari keranjang!";
    }
}

// Update jumlah di keranjang
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $index => $quantity) {
        if (isset($_SESSION['cart'][$index])) {
            $_SESSION['cart'][$index]['quantity'] = $quantity;
        }
    }
    $success_message = "Keranjang berhasil diperbarui!";
}

// Kosongkan keranjang
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = array();
    $success_message = "Keranjang berhasil dikosongkan!";
}

// Hitung total belanja
function hitungTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['harga'] * $item['quantity'];
    }
    return $total;
}

// Hitung jumlah item di keranjang
function hitungItemKeranjang() {
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    return $count;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online - E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --accent-color: #f8f9fc;
            --dark-color: #5a5c69;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }
        
        body {
            font-family: 'Nunito', 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fc;
            color: #5a5c69;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary-color);
            letter-spacing: 0.05em;
        }
        
        .nav-link {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .cart-icon {
            position: relative;
        }
        
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            font-size: 0.6rem;
            font-weight: bold;
            padding: 0.25rem 0.4rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 30px;
            border-radius: 0 0 10px 10px;
        }
        
        .hero-title {
            font-weight: 800;
            font-size: 2.5rem;
        }
        
        .product-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .product-img {
            height: 180px;
            background-color: #eee;
            border-radius: 10px 10px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-img i {
            font-size: 3rem;
            color: #aaa;
        }
        
        .product-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: var(--dark-color);
        }
        
        .product-price {
            color: var(--primary-color);
            font-size: 1.2rem;
            font-weight: 800;
        }
        
        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        
        .cart-section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .cart-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .cart-item {
            padding: 15px 0;
            border-bottom: 1px solid #f2f2f2;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .cart-item-img {
            width: 60px;
            height: 60px;
            background-color: #eee;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .cart-item-img i {
            font-size: 1.5rem;
            color: #aaa;
        }
        
        .cart-item-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .cart-item-price {
            color: var(--dark-color);
            font-weight: 700;
        }
        
        .cart-summary {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .summary-total {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            padding-top: 10px;
            margin-top: 10px;
            border-top: 1px solid #eee;
        }
        
        .section-title {
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .quantity-input {
            width: 70px;
        }
        
        footer {
            background-color: white;
            color: var(--secondary-color);
            padding: 30px 0;
            border-top: 1px solid #eee;
            margin-top: 50px;
        }
        
        .empty-cart {
            text-align: center;
            padding: 50px 0;
        }
        
        .empty-cart i {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-cart p {
            font-size: 1.2rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="user.php">
                <i class="fas fa-store me-2"></i>TokoKita
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="user.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Promo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Bantuan</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#cart-section">
                            <div class="cart-icon">
                                <i class="fas fa-shopping-cart fa-lg"></i>
                                <?php if (hitungItemKeranjang() > 0): ?>
                                <span class="badge rounded-pill bg-danger cart-badge"><?php echo hitungItemKeranjang(); ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-user me-1"></i> Masuk
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="hero-title mb-3">Belanja Online Mudah dan Aman</h1>
                    <p class="lead mb-4">Temukan berbagai produk berkualitas dengan harga terbaik di toko online kami.</p>
                    <a href="#products" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-shopping-bag me-2"></i>Belanja Sekarang
                    </a>
                </div>
                <div class="col-md-6 d-none d-md-block text-center">
                    <i class="fas fa-shopping-basket fa-5x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Products Section -->
        <section id="products" class="mb-5">
            <h2 class="section-title">Produk Terbaru</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <?php
                $query = "SELECT * FROM produk WHERE stok > 0 ORDER BY id DESC";
                $result = mysqli_query($conn, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="col">';
                        echo '<div class="card product-card">';
                        echo '<div class="product-img">';
                        echo '<i class="fas fa-box"></i>';
                        echo '</div>';
                        
                        // Status badge
                        if ($row['stok'] < 5) {
                            echo '<span class="badge bg-warning text-dark stock-badge">Stok Terbatas</span>';
                        }
                        
                        echo '<div class="card-body">';
                        echo '<h5 class="product-title">' . $row['nama'] . '</h5>';
                        echo '<p class="card-text small text-muted mb-2">' . substr($row['deskripsi'], 0, 80) . '...</p>';
                        echo '<div class="d-flex justify-content-between align-items-center mb-3">';
                        echo '<p class="product-price mb-0">Rp ' . number_format($row['harga'], 0, ',', '.') . '</p>';
                        echo '<span class="badge bg-light text-dark">Stok: ' . $row['stok'] . '</span>';
                        echo '</div>';
                        
                        echo '<form method="POST" class="d-flex gap-2">';
                        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                        echo '<div class="input-group input-group-sm">';
                        echo '<span class="input-group-text">Qty</span>';
                        echo '<input type="number" class="form-control" name="quantity" value="1" min="1" max="' . $row['stok'] . '">';
                        echo '</div>';
                        echo '<button type="submit" name="add_to_cart" class="btn btn-primary btn-sm flex-grow-1">';
                        echo '<i class="fas fa-cart-plus me-1"></i> Tambah';
                        echo '</button>';
                        echo '</form>';
                        
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-12">';
                    echo '<div class="alert alert-info">';
                    echo '<i class="fas fa-info-circle me-2"></i> Tidak ada produk yang tersedia.';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </section>

        <!-- Cart Section -->
        <section id="cart-section" class="cart-section">
            <div class="cart-header">
                <h2 class="section-title">Keranjang Belanja</h2>
            </div>
            
            <?php if (count($_SESSION['cart']) > 0): ?>
                <form method="POST" action="user.php#cart-section">
                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <div class="cart-item">
                            <div class="row align-items-center">
                                <div class="col-md-1">
                                    <div class="cart-item-img">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h5 class="cart-item-title"><?php echo $item['nama']; ?></h5>
                                    <p class="cart-item-price">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Qty</span>
                                        <input type="number" class="form-control quantity-input" name="quantities[<?php echo $index; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <span class="fw-bold">Rp <?php echo number_format($item['harga'] * $item['quantity'], 0, ',', '.'); ?></span>
                                </div>
                                <div class="col-md-1 text-end">
                                    <a href="user.php?remove=<?php echo $index; ?>#cart-section" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="d-flex justify-content-between mt-3">
                        <button type="submit" name="update_cart" class="btn btn-outline-primary">
                            <i class="fas fa-sync-alt me-2"></i>Update Keranjang
                        </button>
                        <a href="user.php?clear=1#cart-section" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
                            <i class="fas fa-trash me-2"></i>Kosongkan Keranjang
                        </a>
                    </div>
                </form>
                
                <div class="cart-summary">
                    <h5 class="mb-3">Ringkasan Belanja</h5>
                    <div class="summary-item">
                        <span>Total Harga (<?php echo hitungItemKeranjang(); ?> barang)</span>
                        <span>Rp <?php echo number_format(hitungTotal(), 0, ',', '.'); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Biaya Pengiriman</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="summary-total">
                        <span>Total Pembayaran</span>
                        <span>Rp <?php echo number_format(hitungTotal(), 0, ',', '.'); ?></span>
                    </div>
                    
                    <button class="btn btn-primary w-100 mt-3 py-2">
                        <i class="fas fa-credit-card me-2"></i>Checkout
                    </button>
                </div>
            <?php else: ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Keranjang belanja Anda masih kosong</p>
                    <a href="#products" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3">TokoKita</h5>
                    <p>Toko online terpercaya dengan berbagai pilihan produk berkualitas dan harga terbaik.</p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="mb-3">Tautan</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-secondary">Tentang Kami</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary">Bantuan</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="mb-3">Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> info@tokokita.com</li>
                        <li><i class="fas fa-phone me-2"></i> 0813-9549-6936</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Sumedang, Indonesia</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-3">Ikuti Kami</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary fs-4"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-secondary fs-4"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-secondary fs-4"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-secondary fs-4"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <small>&copy; 2025 TokoKita. Semua hak dilindungi.</small>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JavaScript and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>