<?php
session_start();
include 'config/koneksi.php';

if(empty($_SESSION['keranjang']) || !isset($_SESSION['keranjang'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Keranjang kosong, silakan pesan menu terlebih dahulu',
        icon: 'warning',
        confirmButtonColor: '#1a8f50'
    });
</script></body>";
    echo "<script>location='index.php';</script>";
    exit();
}

$nama_user = "";
$alamat_user = "";
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $q_user = $koneksi->query("SELECT nama_lengkap, alamat_lengkap FROM user WHERE id_user='$id_user'");
    if ($q_user && $q_user->num_rows > 0) {
        $data_user = $q_user->fetch_assoc();
        $nama_user = $data_user['nama_lengkap'];
        $alamat_user = $data_user['alamat_lengkap'];
    }
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
            --primary-green: #1a8f50; /* Wartan 600 */
            --bg-cream: #f8fafc; /* Slate 50 */
            --text-dark: #1e293b; /* Slate 800 */
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
    <?php include 'components/header.php'; ?>
    <div class="container mb-5" style="max-width: 800px;">
        <h2 class="fw-bold mb-4">Keranjang Pesanan</h2>
        
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-3 p-md-4">
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap">
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
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">Informasi Pemesan</h4>
                <form action="checkout_action.php" method="POST">
                    <input type="hidden" name="total_belanja" value="<?php echo $total_belanja; ?>">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_pemesan" class="form-control p-3" required placeholder="Masukkan nama Anda" value="<?php echo htmlspecialchars($nama_user); ?>" style="border-radius: 10px; background-color: #f8f9fa;">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Alamat / Nomor Meja</label>
                        <textarea name="alamat_pengiriman" class="form-control p-3" rows="3" placeholder="Masukkan alamat pengiriman atau tulis 'Makan di Tempat - Meja X'" required style="border-radius: 10px; background-color: #f8f9fa;"><?php echo htmlspecialchars($alamat_user); ?></textarea>
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Metode Pembayaran</label>
                        <div class="d-flex gap-3 flex-column flex-sm-row">
                            <label class="flex-fill border rounded-3 p-3 text-center cursor-pointer payment-option" style="cursor: pointer; transition: 0.3s; background-color: #fff;">
                                <input type="radio" name="metode_pembayaran" value="cash" class="d-none" required checked>
                                <i class="bi bi-cash-coin fs-2 text-success mb-2 d-block"></i>
                                <span class="fw-bold d-block">Bayar di Kasir (Cash)</span>
                            </label>
                            <label class="flex-fill border rounded-3 p-3 text-center cursor-pointer payment-option" style="cursor: pointer; transition: 0.3s; background-color: #fff;">
                                <input type="radio" name="metode_pembayaran" value="qris" class="d-none" required>
                                <i class="bi bi-qr-code-scan fs-2 text-primary mb-2 d-block"></i>
                                <span class="fw-bold d-block">Bayar Sekarang (QRIS)</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" name="checkout" class="btn btn-green w-100 py-3 fw-bold fs-5">Selesaikan Pesanan</button>
                    
                    <style>
                        .payment-option:has(input:checked) {
                            border-color: var(--primary-green) !important;
                            background-color: #f0fdf4 !important;
                            box-shadow: 0 0 0 2px var(--primary-green);
                        }
                    </style>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>
</body>
</html>
