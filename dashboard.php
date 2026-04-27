<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'config/koneksi.php';

// 1. Hitung Pesanan Baru (Status: Masuk)
$q_pesanan_baru = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status_pengiriman = 'masuk'");
$pesanan_baru = mysqli_fetch_assoc($q_pesanan_baru)['total'];

// 2. Hitung Omzet Hari Ini (Status: Selesai, Tanggal: Hari Ini)
$q_omzet = mysqli_query($koneksi, "SELECT SUM(total_harga) as total FROM pesanan WHERE status_pengiriman = 'selesai' AND DATE(tanggal) = CURDATE()");
$omzet_hari_ini = mysqli_fetch_assoc($q_omzet)['total'];
$omzet_hari_ini = $omzet_hari_ini ? $omzet_hari_ini : 0; // Jika belum ada yang beli, jadikan 0

// 3. Hitung Total Menu
$q_total_menu = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
$total_menu = mysqli_fetch_assoc($q_total_menu)['total'];

// 4. Hitung Stok Habis
$q_stok_habis = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk WHERE status = 'habis'");
$stok_habis = mysqli_fetch_assoc($q_stok_habis)['total'];

// 5. Ambil 5 Pesanan Terakhir untuk Tabel Bawah
$q_pesanan_terakhir = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY tanggal DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Wartan Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>.bg-wartan { background-color: #1a8f50; }</style>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-wartan text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-xl font-bold">Wartan Admin</h1>
        <div class="flex items-center gap-4">
            <span class="text-sm">Halo, <?php echo $_SESSION['nama_admin']; ?></span>
            <a href="actions/logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition">Logout</a>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-60px)]">
        
        <div class="w-64 bg-white shadow-lg p-4">
            <ul class="space-y-2 text-gray-700">
                <div class="text-xs font-bold text-gray-400 mb-2 uppercase">Menu Utama</div>
                <li><a href="dashboard.php" class="block px-4 py-2 bg-wartan text-white rounded font-semibold">Dashboard</a></li>
                <li><a href="index_admin.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Kelola Menu</a></li>
                <li><a href="pesanan.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Pesanan Masuk</a></li>
                <li><a href="laporan.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Laporan</a></li>
            </ul>
        </div>

        <div class="flex-1 p-8 overflow-y-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Ringkasan Warung</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                
                <div class="bg-blue-600 text-white p-6 rounded-lg shadow-md relative overflow-hidden">
                    <h3 class="text-sm font-semibold uppercase mb-1">Pesanan Baru</h3>
                    <div class="text-4xl font-bold mb-2"><?php echo $pesanan_baru; ?></div>
                    <p class="text-sm text-blue-200 mb-4">Perlu disiapkan</p>
                    <a href="pesanan.php" class="text-sm font-semibold text-white hover:underline">Lihat Detail &rarr;</a>
                </div>

                <div class="bg-emerald-500 text-white p-6 rounded-lg shadow-md relative overflow-hidden">
                    <h3 class="text-sm font-semibold uppercase mb-1">Omzet Hari Ini</h3>
                    <div class="text-3xl font-bold mb-2">Rp <?php echo number_format($omzet_hari_ini, 0, ',', '.'); ?></div>
                    <p class="text-sm text-emerald-100"><?php echo date('d M Y'); ?></p>
                </div>

                <div class="bg-amber-400 text-white p-6 rounded-lg shadow-md relative overflow-hidden">
                    <h3 class="text-sm font-semibold uppercase mb-1">Total Menu</h3>
                    <div class="text-4xl font-bold mb-2"><?php echo $total_menu; ?></div>
                </div>

                <div class="bg-red-500 text-white p-6 rounded-lg shadow-md relative overflow-hidden">
                    <h3 class="text-sm font-semibold uppercase mb-1">Stok Habis</h3>
                    <div class="text-4xl font-bold mb-2"><?php echo $stok_habis; ?></div>
                    <?php if($stok_habis > 0) { ?>
                        <p class="text-sm text-red-200 mt-2 font-bold">⚠️ Segera Perbarui!</p>
                    <?php } ?>
                </div>
            </div>

            <h3 class="font-bold text-gray-700 mb-3 text-lg">Pesanan Terakhir</h3>
            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="p-4 font-semibold text-gray-600">Pemesan</th>
                            <th class="p-4 font-semibold text-gray-600">Total</th>
                            <th class="p-4 font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        if (mysqli_num_rows($q_pesanan_terakhir) > 0) {
                            while ($row = mysqli_fetch_assoc($q_pesanan_terakhir)) {
                        ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4 text-gray-800"><?php echo $row['nama']; ?></td>
                                    <td class="p-4 text-gray-700">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    <td class="p-4">
                                        <?php 
                                        if($row['status_pengiriman'] == 'masuk') {
                                            echo '<span class="bg-gray-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Masuk</span>';
                                        } else if($row['status_pengiriman'] == 'disiapkan') {
                                            echo '<span class="bg-blue-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Disiapkan</span>';
                                        } else {
                                            echo '<span class="bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Selesai</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php 
                            } 
                        } else { 
                            echo '<tr><td colspan="3" class="p-6 text-center text-gray-500">Belum ada pesanan masuk.</td></tr>';
                        } 
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>