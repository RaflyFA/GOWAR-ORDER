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
    <title>Kelola Menu - Wartan Admin</title>
    <!-- Modern Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        wartan: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#1a8f50',
                            700: '#15803d',
                            800: '#147340',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-wartan-500 selection:text-white">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Modern Sidebar -->
        <aside class="w-72 bg-white border-r border-slate-200 flex flex-col transition-all duration-300 z-20">
            <!-- Logo Area -->
            <div class="h-20 flex items-center px-8 border-b border-slate-100">
                <div class="flex items-center gap-3 text-wartan-600">
                    <div class="p-2 bg-wartan-50 rounded-xl">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    </div>
                    <span class="text-2xl font-extrabold tracking-tight">GOWAR</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto py-6 px-4">
                <div class="text-xs font-bold text-slate-400 mb-3 ml-4 uppercase tracking-wider">Menu Utama</div>
                <ul class="space-y-1.5">
                    <li>
                        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-wartan-600 rounded-xl font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="index_admin.php" class="flex items-center gap-3 px-4 py-3 bg-wartan-600 text-white rounded-xl font-semibold shadow-md shadow-green-500/20 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            Kelola Menu
                        </a>
                    </li>
                    <li>
                        <a href="pesanan.php" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-wartan-600 rounded-xl font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Pesanan Masuk
                        </a>
                    </li>
                    <li>
                        <a href="laporan.php" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-wartan-600 rounded-xl font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Laporan
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Profile Area Bottom -->
            <div class="p-4 border-t border-slate-100">
                <div class="bg-slate-50 rounded-xl p-4 flex items-center justify-between border border-slate-100">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-10 h-10 rounded-full bg-wartan-100 text-wartan-600 flex items-center justify-center font-bold flex-shrink-0">
                            <?php echo substr($_SESSION['nama_admin'], 0, 1); ?>
                        </div>
                        <div class="truncate">
                            <p class="text-sm font-bold text-slate-800 truncate"><?php echo $_SESSION['nama_admin']; ?></p>
                            <p class="text-xs text-slate-500">Admin</p>
                        </div>
                    </div>
                </div>
                <a href="actions/logout.php" class="mt-2 w-full flex items-center justify-center gap-2 px-4 py-2 text-sm text-red-600 font-bold hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 relative">
            
            <!-- Top Header -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 z-10 sticky top-0">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Kelola Menu Makanan</h1>
                    <p class="text-sm text-slate-500">Tambah, ubah, atau hapus menu yang tersedia di warung.</p>
                </div>
                <div class="flex items-center gap-4 text-sm font-medium text-slate-500">
                    <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-slate-200 shadow-sm">
                        <svg class="w-4 h-4 text-wartan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?php echo date('d F Y'); ?>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-8">
                
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-slate-800">Daftar Menu Makanan</h2>
                    <a href="tambah_menu.php" class="bg-wartan-600 hover:bg-wartan-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-green-500/30 transition-all hover:-translate-y-0.5 flex items-center gap-2 font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Menu Baru
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 w-16">No</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Info Menu</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Harga</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Status</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php
                                // Cek apakah ada data menu di database
                                if (mysqli_num_rows($hasil_menu) > 0) {
                                    $no = 1;
                                    // Looping data
                                    while ($row = mysqli_fetch_assoc($hasil_menu)) {
                                ?>
                                        <tr class="hover:bg-slate-50/80 transition-colors group">
                                            <td class="px-6 py-4 text-slate-500 font-medium">
                                                <?php echo $no++; ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-4">
                                                    <?php if(!empty($row['gambar'])): ?>
                                                    <div class="w-12 h-12 rounded-lg bg-slate-100 flex-shrink-0 overflow-hidden border border-slate-200">
                                                        <img src="assets/uploads/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_menu']; ?>" class="w-full h-full object-cover">
                                                    </div>
                                                    <?php else: ?>
                                                    <div class="w-12 h-12 rounded-lg bg-slate-100 flex-shrink-0 flex items-center justify-center text-slate-400 border border-slate-200">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="font-bold text-slate-800 text-base mb-0.5 group-hover:text-wartan-600 transition-colors"><?php echo $row['nama_menu']; ?></div>
                                                        <div class="text-sm text-slate-500 line-clamp-1 max-w-xs"><?php echo $row['deskripsi']; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-slate-800">
                                                Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php if($row['status'] == 'tersedia') { ?>
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                        Tersedia
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                                        Habis
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex justify-center gap-2">
                                                    <a href="edit_menu.php?id=<?php echo $row['id_produk']; ?>" class="p-2 text-amber-500 bg-amber-50 hover:bg-amber-500 hover:text-white rounded-lg transition-colors border border-amber-200 hover:border-amber-500" title="Edit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </a>
                                                    
                                                    <a href="actions/hapus_menu.php?id=<?php echo $row['id_produk']; ?>" onclick="event.preventDefault(); const href=this.href; Swal.fire({text: 'Apakah Anda yakin ingin menghapus menu ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya', cancelButtonText: 'Batal'}).then((r) => { if(r.isConfirmed) window.location.href=href; })" class="p-2 text-red-500 bg-red-50 hover:bg-red-500 hover:text-white rounded-lg transition-colors border border-red-200 hover:border-red-500" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                <?php 
                                    } 
                                } else { 
                                ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                Belum ada menu yang ditambahkan.<br>Silakan klik tombol "Tambah Menu Baru".
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>