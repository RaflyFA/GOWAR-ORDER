<?php
session_start();
include 'config/koneksi.php'; 

// 1. Ambil data dari FORM keranjang.php
$total_final = $_POST['total_belanja'] ?? 0; 
$nama_pembeli = $_POST['nama_pemesan'] ?? 'Pelanggan';
$id_user = $_SESSION['id_user'] ?? null;

if (!$id_user) {
    echo "<script>alert('Sesi berakhir, silakan login kembali'); window.location.href='login.php';</script>";
    exit;
}

// 2. Simpan ke Database
$query = "INSERT INTO pesanan (id_user, total_pesanan, status_pesanan) 
          VALUES ('$id_user', '$total_final', 'pending')";

if (mysqli_query($koneksi, $query)) {
    $id_pesanan_baru = mysqli_insert_id($koneksi);

    // 3. Simpan detail pesanan dan kosongkan keranjang
    if (isset($_SESSION['keranjang'])) {
        foreach ($_SESSION['keranjang'] as $id_produk => $jumlah) {
            $koneksi->query("INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah) VALUES ('$id_pesanan_baru', '$id_produk', '$jumlah')");
        }
        // Kosongkan keranjang setelah pesanan berhasil dibuat
        unset($_SESSION['keranjang']);
    }

    $metode_pembayaran = $_POST['metode_pembayaran'] ?? 'cash';
    if ($metode_pembayaran === 'qris') {
        header("Location: qris.php?id=$id_pesanan_baru");
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Pesanan Sukses - Wartan</title>
        <style>
            /* Mengambil tema dari daftar.php: Background Hijau + Pola Makanan */
            body {
                background-color: #1a8f50;
                background-image: url('https://www.transparenttextures.com/patterns/food.png');
            }
            .bg-wartan { background-color: #1a8f50; }
            .text-wartan { color: #1a8f50; }
        </style>
    </head>
    <body class="flex items-center justify-center min-h-screen p-4 md:p-6"> <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            
            <div class="bg-wartan text-white p-8 text-center">
                <div class="bg-white/20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold">Pesanan Diterima!</h2>
                <p class="text-green-100 text-sm mt-1">Terima kasih sudah memesan di Wartan</p>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-500 text-sm">ID Pesanan</span>
                        <span class="font-mono font-bold text-gray-800">#GW-<?php echo $id_pesanan_baru; ?></span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-500 text-sm">Pemesan</span>
                        <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($nama_pembeli); ?></span>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 flex justify-between items-center mt-4">
                        <span class="font-bold text-gray-700">Total Bayar</span>
                        <span class="text-xl font-black text-wartan">Rp <?php echo number_format($total_final, 0, ',', '.'); ?></span>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3 bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                    <div class="bg-yellow-400 w-2 h-2 rounded-full animate-pulse"></div>
                    <p class="text-xs text-yellow-800 leading-tight">
                        Pesanan kamu berstatus <strong>Pending</strong>. Silakan lakukan pembayaran di kasir atau tunggu konfirmasi admin.
                    </p>
                </div>

                <div class="mt-8 space-y-3">
                    <a href="index.php" class="block w-full bg-wartan text-white text-center py-3 rounded-xl font-bold shadow-lg hover:bg-green-700 transition duration-300">
                        Kembali Beranda
                    </a>
                    <p class="text-center">
                        <a href="riwayat.php" class="text-sm text-gray-400 hover:text-wartan font-medium transition">
                            Cek Riwayat Pesanan
                        </a>
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">&copy; <?php echo date('Y'); ?> Wartan Order System</p>
            </div>
        </div>

    </body>
    </html>
    <?php
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>