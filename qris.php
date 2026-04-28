<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['status_login'])) {
    header("Location: login.php");
    exit();
}

$id_pesanan = $_GET['id'] ?? null;
if (!$id_pesanan) {
    header("Location: index.php");
    exit();
}

// Ambil data pesanan
$query = "SELECT * FROM pesanan WHERE id_pesanan = '$id_pesanan'";
$result = mysqli_query($koneksi, $query);
$pesanan = mysqli_fetch_assoc($result);

if (!$pesanan) {
    header("Location: index.php");
    exit();
}

// Keamanan: pastikan user hanya bisa melihat QRIS miliknya sendiri
if ($_SESSION['role'] !== 'admin' && $pesanan['id_user'] != $_SESSION['id_user']) {
    header("Location: riwayat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Pembayaran QRIS - Wartan</title>
    <style>
        body {
            background-color: #1a8f50;
            background-image: url('https://www.transparenttextures.com/patterns/food.png');
        }
        .bg-wartan { background-color: #1a8f50; }
        .text-wartan { color: #1a8f50; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 md:p-6">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all text-center">
        
        <div class="bg-wartan text-white p-6">
            <h2 class="text-2xl font-bold">Pembayaran QRIS</h2>
            <p class="text-green-100 text-sm mt-1">Scan kode QR di bawah ini menggunakan aplikasi e-Wallet atau M-Banking Anda.</p>
        </div>

        <div class="p-6">
            <div class="bg-gray-50 rounded-xl p-4 flex justify-between items-center mb-6 border border-gray-200 shadow-sm">
                <span class="font-bold text-gray-700">Total Tagihan</span>
                <span class="text-xl font-black text-wartan">Rp <?php echo number_format($pesanan['total_pesanan'], 0, ',', '.'); ?></span>
            </div>

            <!-- QR Code -->
            <div class="bg-white p-4 border-2 border-dashed border-gray-300 rounded-2xl inline-block mb-6 mx-auto">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=WartanOrder_<?php echo $id_pesanan; ?>_<?php echo $pesanan['total_pesanan']; ?>" alt="QRIS Code" class="w-48 h-48 mx-auto">
            </div>

            <p class="text-sm text-gray-500 mb-6 px-4">
                Pastikan nama merchant adalah <strong>Wartan Order System</strong> dan nominal sesuai tagihan.
            </p>

            <div class="space-y-3">
                <button onclick="sudahBayar()" class="block w-full bg-wartan text-white text-center py-3.5 rounded-xl font-bold shadow-lg hover:bg-green-700 transition duration-300 text-lg">
                    Saya Sudah Bayar
                </button>
                <a href="riwayat.php" class="block w-full bg-gray-100 text-gray-700 text-center py-3 rounded-xl font-bold hover:bg-gray-200 transition duration-300">
                    Bayar Nanti
                </a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function sudahBayar() {
            Swal.fire({
                title: 'Verifikasi Pembayaran',
                text: 'Terima kasih! Admin akan segera memverifikasi pembayaran Anda.',
                icon: 'success',
                confirmButtonColor: '#3d6a4a',
                confirmButtonText: 'Tutup & Lihat Pesanan'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'riwayat.php';
                }
            });
        }
    </script>
</body>
</html>
