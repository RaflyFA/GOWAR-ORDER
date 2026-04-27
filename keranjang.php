<?php
session_start();
include 'config/koneksi.php';

if(empty($_SESSION['keranjang']) || !isset($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang kosong, silakan pesan menu terlebih dahulu');</script>";
    echo "<script>location='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Pesanan - Wartan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-green: #3d6a4a;
            --bg-cream: #f9f8f3;
            --text-dark: #1a1a1a;
        }
        body { 
            background-color: var(--bg-cream); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--text-dark);
        }
        .btn-green { background-color: var(--primary-green); color: white; border-radius: 10px; }
        .btn-green:hover { background-color: #2b4d34; color: white; }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5" style="max-width: 800px;">
        <h2 class="fw-bold mb-4">Keranjang Pesanan</h2>
        
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_belanja = 0;
                            foreach($_SESSION['keranjang'] as $id_produk => $jumlah): 
                                $ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'");
                                $pecah = $ambil->fetch_assoc();
                                $subtotal = $pecah['harga'] * $jumlah;
                                $total_belanja += $subtotal;
                            ?>
                            <tr>
                                <td class="fw-semibold"><?php echo htmlspecialchars($pecah['nama_menu']); ?></td>
                                <td>Rp <?php echo number_format($pecah['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $jumlah; ?></td>
                                <td class="fw-bold">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                <td>
                                    <a href="hapus_keranjang.php?id=<?php echo $id_produk ?>" class="btn btn-danger btn-sm" style="border-radius: 8px;"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end pt-4">Total Belanja:</th>
                                <th colspan="2" class="fs-5 text-success pt-4" style="color: var(--primary-green) !important;">Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="index.php" class="btn btn-outline-secondary" style="border-radius: 10px;">Lanjut Belanja</a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">Informasi Pemesan</h4>
                <form action="checkout_action.php" method="POST">
                    <input type="hidden" name="total_belanja" value="<?php echo $total_belanja; ?>">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_pemesan" class="form-control p-3" required placeholder="Masukkan nama Anda" style="border-radius: 10px; background-color: #f8f9fa;">
                    </div>
                    <button type="submit" name="checkout" class="btn btn-green w-100 py-3 fw-bold fs-5">Selesaikan Pesanan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="pt-5 pb-4 mt-5" style="background-color: #1f2937; color: #f9fafb;">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background-color: var(--primary-green);">
                        <i class="bi bi-shop text-white fs-6"></i>
                    </div>
                    <span class="fs-5 fw-bold text-white">Wartan.</span>
                </div>
                <p class="mb-0 text-white-50" style="font-size: 0.85rem;">&copy; <?php echo date('Y'); ?> Wartan Order System. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
