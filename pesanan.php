<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'config/koneksi.php';

// Mengambil data pesanan, diurutkan dari yang terbaru (DESC)
$query_pesanan = "SELECT * FROM pesanan ORDER BY id_pesanan DESC";
$hasil_pesanan = mysqli_query($koneksi, $query_pesanan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk - Wartan Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-wartan { background-color: #1a8f50; }
        .text-wartan { color: #1a8f50; }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-wartan text-white p-4 shadow-md flex justify-between items-center">
        <div class="flex items-center gap-2">
            <h1 class="text-xl font-bold">Wartan Admin</h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm">Halo, <?php echo $_SESSION['nama_admin']; ?></span>
            <a href="actions/logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition">Logout</a>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-60px)]">
        
        <div class="w-64 bg-white shadow-lg p-4">
            <ul class="space-y-2">
                <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Dashboard</a></li>
                <li><a href="index_admin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Kelola Menu</a></li>
                <li><a href="pesanan.php" class="block px-4 py-2 bg-wartan text-white rounded font-semibold">Pesanan Masuk</a></li>
                <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Laporan</a></li>
            </ul>
        </div>

        <div class="flex-1 p-8 overflow-y-auto">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Pesanan Masuk</h2>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="p-4 font-semibold text-gray-600">ID</th>
                            <th class="p-4 font-semibold text-gray-600">Nama Pemesan</th>
                            <th class="p-4 font-semibold text-gray-600">Tipe</th>
                            <th class="p-4 font-semibold text-gray-600">Total</th>
                            <th class="p-4 font-semibold text-gray-600">Status</th>
                            <th class="p-4 font-semibold text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        
                        <?php
                        if (mysqli_num_rows($hasil_pesanan) > 0) {
                            while ($row = mysqli_fetch_assoc($hasil_pesanan)) {
                        ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4 text-gray-700 font-bold">#WRT-<?php echo $row['id_pesanan']; ?></td>
                                    <td class="p-4">
                                        <div class="text-gray-800 font-medium"><?php echo $row['nama']; ?></div>
                                        <div class="text-xs text-gray-500"><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></div>
                                    </td>
                                    <td class="p-4">
                                        <?php if($row['tipe_pesanan'] == 'delivery') { ?>
                                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded">Delivery</span>
                                        <?php } else { ?>
                                            <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-2.5 py-1 rounded">Pickup</span>
                                        <?php } ?>
                                    </td>
                                    <td class="p-4 text-gray-700">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    <td class="p-4">
                                        <?php 
                                        if($row['status_pengiriman'] == 'masuk') {
                                            echo '<span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded border border-yellow-200">Masuk</span>';
                                        } else if($row['status_pengiriman'] == 'disiapkan') {
                                            echo '<span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded border border-blue-200">Disiapkan</span>';
                                        } else {
                                            echo '<span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded border border-green-200">Selesai</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="detail_pesanan.php?id=<?php echo $row['id_pesanan']; ?>" class="border border-green-500 text-green-600 hover:bg-green-50 px-3 py-1 rounded text-sm transition">Detail</a>
                                    </td>
                                </tr>
                        <?php 
                            } 
                        } else { 
                        ?>
                            <tr><td colspan="6" class="p-8 text-center text-gray-500">Belum ada pesanan masuk.</td></tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>