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

// Fungsi untuk menambahkan produk baru
if (isset($_POST['create'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    
    $query = "INSERT INTO produk (nama, harga, deskripsi, stok) VALUES ('$nama', $harga, '$deskripsi', $stok)";
    if (mysqli_query($conn, $query)) {
        $success_message = "Produk berhasil ditambahkan";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Fungsi untuk mengupdate produk
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    
    $query = "UPDATE produk SET nama='$nama', harga=$harga, deskripsi='$deskripsi', stok=$stok WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $success_message = "Produk berhasil diperbarui";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Fungsi untuk menghapus produk
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $query = "DELETE FROM produk WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $success_message = "Produk berhasil dihapus";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Ambil data untuk edit
$nama_edit = "";
$harga_edit = "";
$deskripsi_edit = "";
$stok_edit = "";
$id_edit = "";

if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $query = "SELECT * FROM produk WHERE id=$id_edit";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nama_edit = $row['nama'];
        $harga_edit = $row['harga'];
        $deskripsi_edit = $row['deskripsi'];
        $stok_edit = $row['stok'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            height: 100vh;
            background-color:rgb(26, 102, 179);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            padding-top: 20px;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 10px 20px;
            font-size: 1.1em;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(82, 34, 177, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .action-buttons .btn {
            margin-right: 5px;
        }
        
        .table {
            vertical-align: middle;
        }
        
        .product-count {
            background-color: #17a2b8;
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 14px;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .dashboard-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #343a40;
        }
        
        .status-card {
            border-left: 4px solid;
            border-radius: 4px;
        }
        
        .status-card.products {
            border-left-color: #28a745;
        }
        
        .status-card.out-of-stock {
            border-left-color: #dc3545;
        }
        
        .status-card.low-stock {
            border-left-color: #ffc107;
        }
        
        .status-card .card-body {
            padding: 20px;
        }
        
        .status-card .status-value {
            font-size: 2rem;
            font-weight: 700;
            color: #343a40;
        }
        
        .status-card .status-label {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-card .icon {
            font-size: 2rem;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="px-3 mb-4">
            <h4 class="text-center">Admin Panel</h4>
            <hr>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-box"></i> Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-shopping-cart"></i> Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-users"></i> Pengguna
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-chart-bar"></i> Laporan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>
            </li>
            <li class="nav-item mt-5">
                <a class="nav-link text-danger" href="#">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </li>
        </ul>
    </div>

    <!-- Content Area -->
    <div class="content">
        <!-- Header -->
        <div class="dashboard-header mb-4">
            <div>
                <h2 class="dashboard-title">Dashboard</h2>
                <p class="text-muted">Kelola produk e-commerce Anda</p>
            </div>
            <div class="d-flex align-items-center">
                <div class="avatar">A</div>
                <div>
                    <div class="fw-bold">Admin</div>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>
        </div>

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

        <!-- Status Cards -->
        <div class="row mb-4">
            <?php
            // Get total products
            $query = "SELECT COUNT(*) as total FROM produk";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $total_products = $row['total'];
            
            // Get out of stock products
            $query = "SELECT COUNT(*) as out_of_stock FROM produk WHERE stok = 0";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $out_of_stock = $row['out_of_stock'];
            
            // Get low stock products (less than 5)
            $query = "SELECT COUNT(*) as low_stock FROM produk WHERE stok > 0 AND stok < 5";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $low_stock = $row['low_stock'];
            ?>
            
            <div class="col-md-4">
                <div class="card status-card products">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="status-value"><?php echo $total_products; ?></div>
                            <div class="status-label">Total Produk</div>
                        </div>
                        <div class="icon text-success">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card status-card out-of-stock">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="status-value"><?php echo $out_of_stock; ?></div>
                            <div class="status-label">Stok Habis</div>
                        </div>
                        <div class="icon text-danger">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card status-card low-stock">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="status-value"><?php echo $low_stock; ?></div>
                            <div class="status-label">Stok Rendah</div>
                        </div>
                        <div class="icon text-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Form -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-<?php echo $id_edit ? 'edit' : 'plus'; ?> me-2"></i>
                <?php echo $id_edit ? 'Edit Produk' : 'Tambah Produk Baru'; ?>
            </div>
            <div class="card-body">
                <form method="POST" class="form">
                    <?php if ($id_edit): ?>
                        <input type="hidden" name="id" value="<?php echo $id_edit; ?>">
                    <?php endif; ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama_edit; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="harga" class="form-label">Harga (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="harga" name="harga" value="<?php echo $harga_edit; ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?php echo $deskripsi_edit; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $stok_edit; ?>" required>
                    </div>
                    
                    <div class="d-flex">
                        <?php if ($id_edit): ?>
                            <button type="submit" name="update" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Produk
                            </button>
                            <a href="admin.php" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                        <?php else: ?>
                            <button type="submit" name="create" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i> Tambah Produk
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Product List -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-list me-2"></i> Daftar Produk
                    </div>
                    <div class="product-count">
                        <i class="fas fa-box me-1"></i> <?php echo $total_products; ?> Produk
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM produk ORDER BY id DESC";
                            $result = mysqli_query($conn, $query);
                            
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['nama'] . "</td>";
                                    echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                    echo "<td>" . $row['stok'] . "</td>";
                                    
                                    // Status
                                    if ($row['stok'] == 0) {
                                        echo "<td><span class='badge bg-danger'>Stok Habis</span></td>";
                                    } elseif ($row['stok'] < 5) {
                                        echo "<td><span class='badge bg-warning text-dark'>Stok Menipis</span></td>";
                                    } else {
                                        echo "<td><span class='badge bg-success'>Tersedia</span></td>";
                                    }
                                    
                                    echo "<td class='action-buttons'>
                                            <a href='admin.php?edit=" . $row['id'] . "' class='btn btn-sm btn-primary'>
                                                <i class='fas fa-edit'></i> Edit
                                            </a>
                                            <a href='admin.php?delete=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus produk ini?\")'>
                                                <i class='fas fa-trash'></i> Hapus
                                            </a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Tidak ada produk yang tersedia.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JavaScript and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>