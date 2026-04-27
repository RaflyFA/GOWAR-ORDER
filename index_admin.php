<?php
session_start();

// Proteksi Halaman: Jika belum login atau bukan admin, tendang ke halaman login
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Panggil koneksi database
include 'config/koneksi.php';

// Query untuk mengambil semua data dari tabel produk
$query_menu = "SELECT * FROM produk ORDER BY id_produk DESC";
$hasil_menu = mysqli_query($koneksi, $query_menu);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wartan Admin - Kelola Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-wartan { background-color: #1a8f50; }
        .text-wartan { color: #1a8f50; }
        .hover-wartan:hover { background-color: #147340; }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-wartan text-white p-4 shadow-md flex justify-between items-center">
        <div class="flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            <span class="text-xl font-bold">Wartan Admin</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm">Halo, <?php echo $_SESSION['nama_admin']; ?></span>
            <a href="actions/logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition">Logout</a>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-60px)]">
        
        <div class="w-64 bg-white shadow-lg p-4">
            <ul class="space-y-2">
                <li>
                    <a href="dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Dashboard</a>
                </li>
                <li>
                    <a href="index_admin.php" class="block px-4 py-2 bg-wartan text-white rounded font-semibold">Kelola Menu</a>
                </li>
                <li>
                    <a href="pesanan.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Pesanan Masuk</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Laporan</a>
                </li>
            </ul>
        </div>

        <div class="flex-1 p-8 overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Daftar Menu Makanan</h1>
                <a href="tambah_menu.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Menu
                </a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="p-4 font-semibold text-gray-600">No</th>
                            <th class="p-4 font-semibold text-gray-600">Nama Menu</th>
                            <th class="p-4 font-semibold text-gray-600">Harga</th>
                            <th class="p-4 font-semibold text-gray-600">Status</th>
                            <th class="p-4 font-semibold text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        
                        <?php
                        // Cek apakah ada data menu di database
                        if (mysqli_num_rows($hasil_menu) > 0) {
                            $no = 1;
                            // Looping data (mengulang baris tabel HTML) sebanyak data yang ada
                            while ($row = mysqli_fetch_assoc($hasil_menu)) {
                        ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4 text-gray-700"><?php echo $no++; ?></td>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800"><?php echo $row['nama_menu']; ?></div>
                                        <div class="text-sm text-gray-500"><?php echo $row['deskripsi']; ?></div>
                                    </td>
                                    <td class="p-4 text-gray-700">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td class="p-4">
                                        <?php if($row['status'] == 'tersedia') { ?>
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-200">Tersedia</span>
                                        <?php } else { ?>
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-red-200">Habis</span>
                                        <?php } ?>
                                    </td>
                                    <td class="p-4 flex justify-center gap-2">
                                        <a href="edit_menu.php?id=<?php echo $row['id_produk']; ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white p-2 rounded transition inline-block">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        
                                        <a href="actions/hapus_menu.php?id=<?php echo $row['id_produk']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?');" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition inline-block">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                        <?php 
                            } 
                        } else { 
                        ?>
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500">
                                    Belum ada menu yang ditambahkan. Silakan klik tombol "Tambah Menu".
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>