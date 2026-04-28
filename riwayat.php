<?php
session_start();
include 'config/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Wartan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #1a8f50;
            background-image: url('https://www.transparenttextures.com/patterns/food.png');
        }
        .bg-wartan { background-color: #1a8f50; }
        .text-wartan { color: #1a8f50; }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8">

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6 bg-white p-4 md:p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg text-green-600 hidden sm:block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800">Riwayat Pesanan</h1>
                    <p class="text-xs text-gray-500 mt-1">Daftar pesanan yang pernah Anda buat</p>
                </div>
            </div>
            <a href="index.php" class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-green-600 transition bg-gray-50 hover:bg-green-50 px-4 py-2.5 rounded-xl border border-gray-200 hover:border-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="hidden sm:inline">Kembali</span>
            </a>
        </div>

        <div class="grid gap-4">
            <?php
            // Ambil data pesanan milik user yang sedang login
            $query = "SELECT * FROM pesanan WHERE id_user = '$id_user' ORDER BY id_pesanan DESC";
            $tampil = mysqli_query($koneksi, $query);

            if (mysqli_num_rows($tampil) > 0) {
                while ($data = mysqli_fetch_assoc($tampil)) {
                    // Logika warna status
                    $status_color = ($data['status_pesanan'] == 'pending') ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700';
            ?>
            
            <div class="bg-white rounded-2xl shadow-md overflow-hidden border-l-8 border-green-500">
                <div class="p-5 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">ID Pesanan</span>
                            <span class="font-mono font-bold text-gray-700">#GW-<?php echo $data['id_pesanan']; ?></span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Rp <?php echo number_format($data['total_pesanan'], 0, ',', '.'); ?></h3>
                        <p class="text-xs text-gray-400 mt-1"><?php echo date('d M Y - H:i', strtotime($data['tanggal_pesanan'])); ?></p>
                    </div>

                    <div class="flex items-center justify-between md:justify-end gap-4 border-t md:border-t-0 pt-3 md:pt-0">
                        <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase <?php echo $status_color; ?>">
                            <?php echo $data['status_pesanan']; ?>
                        </span>
                        <a href="detail_pesanan.php?id=<?php echo $data['id_pesanan']; ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold transition">
                            Detail
                        </a>
                    </div>
                </div>
            </div>

            <?php 
                } 
            } else {
                echo "
                <div class='bg-white p-10 rounded-2xl text-center shadow-lg'>
                    <p class='text-gray-500 font-medium'>Belum ada riwayat pesanan.</p>
                    <a href='index.php' class='mt-4 inline-block bg-wartan text-white px-6 py-2 rounded-full font-bold'>Pesan Sekarang</a>
                </div>";
            }
            ?>
        </div>
    </div>

</body>
</html>