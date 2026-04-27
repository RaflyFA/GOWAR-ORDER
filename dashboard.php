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
                            600: '#1a8f50', // Original bg-wartan
                            700: '#15803d',
                            800: '#147340', // Original hover-wartan
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
                    <span class="text-2xl font-extrabold tracking-tight">GOWAR.</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto py-6 px-4">
                <div class="text-xs font-bold text-slate-400 mb-3 ml-4 uppercase tracking-wider">Menu Utama</div>
                <ul class="space-y-1.5">
                    <li>
                        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 bg-wartan-600 text-white rounded-xl font-semibold shadow-md shadow-green-500/20 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="index_admin.php" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-wartan-600 rounded-xl font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            Kelola Menu
                        </a>
                    </li>
                    <li>
                        <a href="pesanan.php" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-wartan-600 rounded-xl font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Pesanan Masuk
                            <?php if($pesanan_baru > 0): ?>
                                <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"><?php echo $pesanan_baru; ?></span>
                            <?php endif; ?>
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
                            <p class="text-xs text-slate-500">Administrator</p>
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
                    <h1 class="text-2xl font-bold text-slate-800">Dashboard Overview</h1>
                    <p class="text-sm text-slate-500">Selamat datang kembali, pantau performa warungmu hari ini.</p>
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
                
                <!-- Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    
                    <!-- Card 1 -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-blue-50 group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Pesanan Baru</p>
                                    <h3 class="text-3xl font-extrabold text-slate-800"><?php echo $pesanan_baru; ?></h3>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                            </div>
                            <div class="flex items-center text-sm">
                                <?php if($pesanan_baru > 0): ?>
                                    <a href="pesanan.php" class="text-blue-600 font-semibold hover:underline flex items-center gap-1">Lihat Pesanan <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
                                <?php else: ?>
                                    <span class="text-slate-400">Belum ada pesanan</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-wartan-50 group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Omzet Hari Ini</p>
                                    <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">Rp <?php echo number_format($omzet_hari_ini, 0, ',', '.'); ?></h3>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-wartan-100 text-wartan-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-wartan-600 font-medium">
                                <span>Pendapatan Selesai</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-amber-50 group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Menu</p>
                                    <h3 class="text-3xl font-extrabold text-slate-800"><?php echo $total_menu; ?></h3>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-slate-500">
                                <a href="index_admin.php" class="hover:text-amber-600 transition-colors">Kelola Menu Makanan</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-red-50 group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Stok Habis</p>
                                    <h3 class="text-3xl font-extrabold text-slate-800"><?php echo $stok_habis; ?></h3>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                            </div>
                            <div class="flex items-center text-sm">
                                <?php if($stok_habis > 0): ?>
                                    <span class="text-red-600 font-bold flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> Segera Perbarui!</span>
                                <?php else: ?>
                                    <span class="text-green-600 font-medium">Stok Aman</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-800">Pesanan Terakhir</h3>
                        <a href="pesanan.php" class="text-sm font-semibold text-wartan-600 hover:text-wartan-800 transition-colors">Lihat Semua</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Pemesan</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Waktu</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Total</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php
                                if (mysqli_num_rows($q_pesanan_terakhir) > 0) {
                                    while ($row = mysqli_fetch_assoc($q_pesanan_terakhir)) {
                                ?>
                                        <tr class="hover:bg-slate-50/80 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs">
                                                        <?php echo substr($row['nama'], 0, 1); ?>
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-slate-800 group-hover:text-wartan-600 transition-colors"><?php echo $row['nama']; ?></div>
                                                        <div class="text-xs text-slate-500">#WRT-<?php echo $row['id_pesanan']; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-600">
                                                <?php echo date('d M, H:i', strtotime($row['tanggal'])); ?>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-slate-800">
                                                Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php 
                                                if($row['status_pengiriman'] == 'masuk') {
                                                    echo '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-500 mr-1.5"></span>Masuk</span>';
                                                } else if($row['status_pengiriman'] == 'disiapkan') {
                                                    echo '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>Disiapkan</span>';
                                                } else {
                                                    echo '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Selesai</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                <?php 
                                    } 
                                } else { 
                                    echo '<tr><td colspan="4" class="px-6 py-12 text-center text-slate-500"><div class="flex flex-col items-center"><svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>Belum ada pesanan masuk.</div></td></tr>';
                                } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>