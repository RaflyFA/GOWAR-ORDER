<?php 
session_start();
include 'config/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wartan - Pesan Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-green: #3d6a4a;
            --bg-cream: #f9f8f3;
            --text-dark: #1a1a1a;
            --secondary-text: #666;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-cream); 
            color: var(--text-dark);
            margin: 0;
        }

        /* Navbar */
        .navbar {
            padding: 20px 0;
            background-color: transparent;
        }
        .navbar-brand img { width: 35px; margin-right: 10px; }
        .logo-text { font-weight: 700; font-size: 1.4rem; color: var(--primary-green); line-height: 1; }
        .logo-sub { font-size: 0.6rem; letter-spacing: 2px; color: var(--secondary-text); text-transform: uppercase; }

        /* Hero Section */
        .hero-container {
            padding: 60px 0;
            display: flex;
            align-items: center;
        }

        .badge-online {
            background-color: #fff;
            border: 1px solid #eee;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 20px;
            color: #b58e3e;
            font-weight: 500;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 25px;
            letter-spacing: -2px;
        }
        .hero-title span { color: var(--primary-green); }

        .hero-desc {
            color: var(--secondary-text);
            font-size: 1.1rem;
            max-width: 500px;
            margin-bottom: 35px;
            line-height: 1.6;
        }

        /* Buttons */
        .btn-green {
            background-color: var(--primary-green);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .btn-outline {
            background-color: #fff;
            color: var(--text-dark);
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            border: 1px solid #eee;
            text-decoration: none;
        }

        /* Image Placeholder */
        .hero-image-wrap {
            position: relative;
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .info-bottom {
            display: flex;
            gap: 30px;
            margin-top: 40px;
            color: var(--secondary-text);
            font-size: 0.9rem;
        }
        .info-item { display: flex; align-items: center; gap: 8px; }
        .info-item i { color: var(--primary-green); }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem !important;
                letter-spacing: -0.5px !important;
                margin-bottom: 10px !important;
                line-height: 1.2 !important;
            }
            .hero-desc {
                font-size: 0.95rem !important;
                margin: 0 auto 20px auto !important;
            }
            .hero-container {
                padding: 20px 0 !important;
            }
            .navbar {
                padding: 10px 0 !important;
            }
            .logo-text { font-size: 1.2rem !important; }
            .info-bottom {
                margin-top: 40px !important;
                gap: 15px !important;
                flex-wrap: wrap;
                justify-content: center !important;
                font-size: 0.85rem !important;
            }
            .hero-image-wrap {
                margin-top: 40px !important;
                margin-left: auto;
                margin-right: auto;
                display: block;
                max-width: 90%;
            }
            .hero-image-wrap img {
                max-width: 100%;
                height: auto;
            }
            .btn-green, .btn-outline {
                justify-content: center !important;
                padding: 12px 24px !important;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <div class="d-flex align-items-center">
            <div class="p-2 bg-success text-white rounded-3 me-2" style="background-color: var(--primary-green) !important;">
                <i class="bi bi-fire"></i>
            </div>
            <div>
                <div class="logo-text">Wartan</div>
                <div class="logo-sub">Pesan Online</div>
            </div>
        </div>
        <div class="ms-auto d-flex align-items-center">
            <?php if(isset($_SESSION['status_login'])): ?>
                <div class="dropdown">
                    <button class="btn btn-outline text-dark dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; font-weight: 500;">
                        <i class="bi bi-person-circle fs-4 me-2" style="color: var(--primary-green);"></i> 
                        <span class="d-none d-sm-inline"><?php echo isset($_SESSION['nama_admin']) ? htmlspecialchars($_SESSION['nama_admin']) : 'User'; ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px; min-width: 200px;">
                        <li><a class="dropdown-item py-2" href="profil.php"><i class="bi bi-geo-alt me-2 text-muted"></i> Profil & Alamat</a></li>
                        <?php if($_SESSION['role'] == 'admin'): ?>
                        <li><a class="dropdown-item py-2" href="dashboard.php"><i class="bi bi-speedometer2 me-2 text-muted"></i> Dashboard Admin</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Keluar</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="nav-link me-2 me-md-4 fw-medium text-dark" style="font-size: 0.95rem;">Masuk</a>
                <a href="daftar.php" class="btn btn-green rounded-pill py-2 px-3 px-md-4" style="border-radius: 10px; font-size: 0.95rem;">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container hero-container">
    <div class="row align-items-center">
        <div class="col-lg-6 text-center text-lg-start">
            <div class="badge-online">
                <i class="bi bi-stars"></i> Pelayanan pemesanan menu online
            </div>
            <h1 class="hero-title">
                Pesan menu favoritmu, <br><span>tanpa antri.</span>
            </h1>
            <p class="hero-desc mx-auto mx-lg-0">
                Wartan memudahkan Anda memesan makanan dan minuman secara online — cepat, akurat, dan langsung sampai ke dapur kami.
            </p>
            
            <div class="d-flex flex-wrap gap-3 mb-4 mb-md-0 justify-content-center justify-content-lg-start">
                <a href="#menu" class="btn-green m-0">
                    <i class="bi bi-journal-text"></i> Lihat Menu
                </a>
                <a href="daftar.php" class="btn-outline m-0">
                    Daftar Gratis
                </a>
            </div>

            <div class="info-bottom mt-4 mt-md-5 mb-5 mb-lg-0">
                <div class="info-item">
                    <i class="bi bi-clock"></i> Pesan 24 jam
                </div>
                <div class="info-item">
                    <i class="bi bi-flower1"></i> Bahan segar
                </div>
            </div>
        </div>

        <div class="col-lg-6 mt-5 pt-3 pt-lg-0">
            <div class="hero-image-wrap">
                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&q=80&w=1000" class="img-fluid" alt="Healthy Food">
            </div>
        </div>
    </div>
</div>

<!-- Promo Spesial -->
<div class="container mb-5" id="promo">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark); letter-spacing: -0.5px;">Promo Spesial</h2>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Penawaran eksklusif hanya untuk Anda hari ini</p>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm" style="height: 200px; position: relative;">
                <img src="assets/uploads/promo1.png" alt="Promo 1" style="width: 100%; height: 100%; object-fit: cover; position: absolute; z-index: 0;">
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.2)); z-index: 1;"></div>
                <div class="card-body position-relative z-2 d-flex flex-column justify-content-center h-100 p-4">
                    <span class="badge bg-danger mb-2" style="width: fit-content; padding: 6px 12px; font-size: 0.8rem;">Diskon 20%</span>
                    <h3 class="text-white fw-bold">Paket Kenyang<br>Makan Siang</h3>
                    <p class="text-white-50 mb-0 mt-2" style="font-size: 0.85rem;">Berlaku setiap Senin - Jumat</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm" style="height: 200px; position: relative;">
                <img src="assets/uploads/promo2.png" alt="Promo 2" style="width: 100%; height: 100%; object-fit: cover; position: absolute; z-index: 0;">
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.2)); z-index: 1;"></div>
                <div class="card-body position-relative z-2 d-flex flex-column justify-content-center h-100 p-4">
                    <span class="badge mb-2" style="background-color: var(--primary-green); width: fit-content; padding: 6px 12px; font-size: 0.8rem;">Spesial Minuman</span>
                    <h3 class="text-white fw-bold">Beli 1 Gratis 1<br>Semua Minuman</h3>
                    <p class="text-white-50 mb-0 mt-2" style="font-size: 0.85rem;">Syarat dan ketentuan berlaku</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pesanan Favorit -->
<div class="container mb-5" id="favorit">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark); letter-spacing: -0.5px;">Pesanan Favorit</h2>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Menu yang paling sering dipesan pelanggan</p>
        </div>
    </div>
    <div class="row g-4">
        <?php
        $query_fav = mysqli_query($koneksi, "SELECT * FROM produk WHERE status='tersedia' ORDER BY RAND() LIMIT 4");
        if(mysqli_num_rows($query_fav) > 0) {
            while($row_fav = mysqli_fetch_assoc($query_fav)) {
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 h-100 p-2" style="border-radius: 16px; background-color: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid rgba(26,143,80,0.1) !important;">
                <div class="card-body p-2 d-flex flex-column">
                    <div class="d-flex justify-content-between mb-2">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-dark);"><?php echo htmlspecialchars($row_fav['kategori_produk']); ?></span>
                        <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                    </div>
                    
                    <?php if(!empty($row_fav['gambar'])): ?>
                        <div style="height: 140px; border-radius: 12px; overflow: hidden; margin-bottom: 12px;">
                            <img src="assets/uploads/<?php echo $row_fav['gambar']; ?>" alt="<?php echo htmlspecialchars($row_fav['nama_menu']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php else: ?>
                        <div style="background-color: var(--bg-cream); height: 140px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <i class="bi bi-image" style="font-size: 2rem; color: #cbd5e1;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <h5 class="card-title fw-bold" style="font-size: 1rem; color: var(--text-dark);"><?php echo htmlspecialchars($row_fav['nama_menu']); ?></h5>
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                        <span class="fw-bold" style="color: var(--primary-green); font-size: 1rem;">Rp <?php echo number_format($row_fav['harga'], 0, ',', '.'); ?></span>
                        <a href="beli.php?id=<?php echo $row_fav['id_produk']; ?>" class="btn btn-sm btn-green" style="border-radius: 8px; padding: 4px 12px; font-size: 0.8rem;">Pesan</a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            }
        } else {
            echo '<div class="col-12 text-muted">Belum ada data menu.</div>';
        }
        ?>
    </div>
</div>

<hr class="border-secondary my-5 opacity-10 container">

<div class="container mt-5 pt-4 pb-5" id="menu">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Menu Hari Ini</h2>
            <p class="text-muted mb-0" style="color: var(--secondary-text);">Pilih menu kesukaanmu dan tambahkan ke keranjang.</p>
        </div>
        <div class="col-md-6 mt-3 mt-md-0 d-flex justify-content-md-end">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-transparent" id="search-addon" style="border-radius: 12px 0 0 12px; border-color: #eee;">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control shadow-none" placeholder="Cari menu..." aria-label="Cari menu" aria-describedby="search-addon" style="border-radius: 0 12px 12px 0; border-color: #eee; border-left: none; background-color: transparent;">
            </div>
        </div>
    </div>

    <!-- Filter Buttons -->
    <?php
    $kat_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : 'Semua';
    ?>
    <div class="d-flex gap-2 mb-4 overflow-x-auto pb-2" style="white-space: nowrap; -webkit-overflow-scrolling: touch;">
        <a href="index.php?kategori=Semua#menu" class="btn <?php echo $kat_aktif == 'Semua' ? 'btn-green' : 'btn-outline'; ?> m-0 flex-shrink-0" style="padding: 8px 24px; border-radius: 10px; text-decoration: none;">Semua</a>
        <a href="index.php?kategori=Makanan#menu" class="btn <?php echo $kat_aktif == 'Makanan' ? 'btn-green' : 'btn-outline'; ?> m-0 flex-shrink-0" style="padding: 8px 24px; border-radius: 10px; text-decoration: none;">Makanan</a>
        <a href="index.php?kategori=Minuman#menu" class="btn <?php echo $kat_aktif == 'Minuman' ? 'btn-green' : 'btn-outline'; ?> m-0 flex-shrink-0" style="padding: 8px 24px; border-radius: 10px; text-decoration: none;">Minuman</a>
    </div>

    <!-- Menu Grid -->
    <div class="row g-4">
        <?php
        if ($kat_aktif != 'Semua') {
            $query_menu = mysqli_query($koneksi, "SELECT * FROM produk WHERE status='tersedia' AND kategori_produk='$kat_aktif' ORDER BY id_produk DESC");
        } else {
            $query_menu = mysqli_query($koneksi, "SELECT * FROM produk WHERE status='tersedia' ORDER BY id_produk DESC");
        }
        if(mysqli_num_rows($query_menu) > 0) {
            while($row = mysqli_fetch_assoc($query_menu)) {
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 h-100 p-2" style="border-radius: 16px; background-color: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <div class="card-body p-2 d-flex flex-column">
                    <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-dark); margin-bottom: 12px; display: block;"><?php echo htmlspecialchars($row['kategori_produk']); ?></span>
                    
                    <?php if(!empty($row['gambar'])): ?>
                        <div style="height: 160px; border-radius: 12px; overflow: hidden; margin-bottom: 16px;">
                            <img src="assets/uploads/<?php echo $row['gambar']; ?>" alt="<?php echo htmlspecialchars($row['nama_menu']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php else: ?>
                        <div style="background-color: var(--bg-cream); height: 160px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                            <i class="bi bi-image" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <h5 class="card-title fw-bold" style="font-size: 1.05rem; color: var(--text-dark);"><?php echo htmlspecialchars($row['nama_menu']); ?></h5>
                    <p class="card-text text-muted" style="font-size: 0.85rem; line-height: 1.5; margin-bottom: 20px;"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="fw-bold" style="color: var(--primary-green); font-size: 1.1rem;">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></span>
                        <a href="beli.php?id=<?php echo $row['id_produk']; ?>" class="btn btn-green btn-sm m-0" style="border-radius: 8px; padding: 6px 14px; font-size: 0.85rem; text-decoration: none;">+ Tambah</a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            }
        } else {
            echo '<div class="col-12 text-center py-5 text-muted">Belum ada menu yang tersedia.</div>';
        }
        ?>
    </div>
</div>

<!-- Footer -->
<footer class="pt-5 pb-4 mt-5" style="background-color: #1f2937; color: #f9fafb;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; background-color: var(--primary-green);">
                        <i class="bi bi-shop text-white fs-5"></i>
                    </div>
                    <span class="fs-4 fw-bold text-white">Wartan.</span>
                </div>
                <p class="text-white-50" style="font-size: 0.9rem; line-height: 1.6;">Solusi terbaik untuk memesan makanan dan minuman lezat tanpa perlu antre panjang. Nikmati hidangan favoritmu sekarang dengan mudah dan cepat.</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="text-white-50 hover-white"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-white-50 hover-white"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-white-50 hover-white"><i class="bi bi-twitter-x fs-5"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-3 col-6">
                <h5 class="text-white fw-semibold mb-3 fs-6">Layanan</h5>
                <ul class="list-unstyled d-flex flex-column gap-2 text-white-50" style="font-size: 0.9rem;">
                    <li><a href="#menu" class="text-decoration-none text-white-50 hover-white">Menu Hari Ini</a></li>
                    <li><a href="#promo" class="text-decoration-none text-white-50 hover-white">Promo Spesial</a></li>
                    <li><a href="#favorit" class="text-decoration-none text-white-50 hover-white">Pesanan Favorit</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-3 col-6">
                <h5 class="text-white fw-semibold mb-3 fs-6">Dukungan</h5>
                <ul class="list-unstyled d-flex flex-column gap-2 text-white-50" style="font-size: 0.9rem;">
                    <li><a href="bantuan.php" class="text-decoration-none text-white-50 hover-white">Pusat Bantuan</a></li>
                    <li><a href="syarat.php" class="text-decoration-none text-white-50 hover-white">Syarat & Ketentuan</a></li>
                    <li><a href="privasi.php" class="text-decoration-none text-white-50 hover-white">Kebijakan Privasi</a></li>
                </ul>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white fw-semibold mb-3 fs-6">Berlangganan Info Diskon</h5>
                <p class="text-white-50 mb-3" style="font-size: 0.9rem;">Dapatkan informasi promo menarik dan diskon eksklusif langsung ke email Anda.</p>
                <div class="input-group mb-3">
                    <input type="email" class="form-control border-0 px-3 py-2" placeholder="Alamat email Anda" aria-label="Email" style="border-radius: 8px 0 0 8px;">
                    <button class="btn text-white px-3 fw-medium" type="button" style="background-color: var(--primary-green); border-radius: 0 8px 8px 0;">Kirim</button>
                </div>
            </div>
        </div>
        
        <hr class="border-secondary mt-5 mb-4 opacity-25">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-0 text-white-50" style="font-size: 0.85rem;">&copy; <?php echo date('Y'); ?> Wartan Order System. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>

<style>
    .hover-white { transition: color 0.3s ease; }
    .hover-white:hover { color: #ffffff !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>