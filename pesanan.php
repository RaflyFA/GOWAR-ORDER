<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'config/koneksi.php';

// ─── PAGINATION ───────────────────────────────────────────────
$per_page  = 10;
$halaman   = isset($_GET['halaman']) && is_numeric($_GET['halaman']) && $_GET['halaman'] > 0
             ? (int)$_GET['halaman'] : 1;
$offset    = ($halaman - 1) * $per_page;

// ─── FILTER STATUS ────────────────────────────────────────────
$filter_status     = isset($_GET['status']) ? $_GET['status'] : 'semua';
$allowed_statuses  = ['semua', 'masuk', 'disiapkan', 'selesai', 'dibatalkan'];
if (!in_array($filter_status, $allowed_statuses)) $filter_status = 'semua';

$where = ($filter_status !== 'semua') ? "WHERE p.status_pesanan = '$filter_status'" : "";

// ─── HITUNG TOTAL UNTUK PAGINATION ────────────────────────────
$q_total    = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan p $where");
$total_data = mysqli_fetch_assoc($q_total)['total'];
$total_page = ceil($total_data / $per_page);
if ($halaman > $total_page && $total_page > 0) $halaman = $total_page;

// ─── AMBIL DATA SESUAI HALAMAN ────────────────────────────────
$query_pesanan = "SELECT p.*, u.nama_lengkap as nama FROM pesanan p LEFT JOIN user u ON p.id_user = u.id_user $where ORDER BY p.id_pesanan DESC LIMIT $per_page OFFSET $offset";
$hasil_pesanan = mysqli_query($koneksi, $query_pesanan);

// ─── HITUNG BADGE TIAP STATUS (untuk tab) ────────────────────
function countStatus($koneksi, $status) {
    $q = mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pesanan WHERE status_pesanan = '$status'");
    return mysqli_fetch_assoc($q)['c'];
}
$cnt_masuk      = countStatus($koneksi, 'masuk');
$cnt_disiapkan  = countStatus($koneksi, 'disiapkan');
$cnt_selesai    = countStatus($koneksi, 'selesai');
$cnt_dibatalkan = countStatus($koneksi, 'dibatalkan');
$cnt_semua      = $cnt_masuk + $cnt_disiapkan + $cnt_selesai + $cnt_dibatalkan;

// Helper: buat URL tetap membawa parameter lain kecuali 'halaman'
function buildUrl($params = []) {
    $base = array_merge($_GET, $params);
    return 'pesanan.php?' . http_build_query($base);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk - GOWAR Admin</title>
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
                        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-wartan-600 rounded-xl font-medium transition-all">
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
                        <a href="pesanan.php" class="flex items-center gap-3 px-4 py-3 bg-wartan-600 text-white rounded-xl font-semibold shadow-md shadow-green-500/20 transition-all">
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
                    <h1 class="text-2xl font-bold text-slate-800">Daftar Pesanan</h1>
                    <p class="text-sm text-slate-500">Kelola dan pantau pesanan pelanggan yang masuk hari ini.</p>
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
                
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

                    <!-- Status Filter Tabs -->
                    <div class="px-6 pt-5 pb-0 border-b border-slate-100 bg-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-slate-800">Daftar Semua Pesanan</h3>
                            <span class="text-sm text-slate-400 font-medium"><?php echo $total_data; ?> total data</span>
                        </div>
                        <div class="flex gap-1 overflow-x-auto">
                            <?php
                            $tabs = [
                                'semua'      => ['label' => 'Semua',      'count' => $cnt_semua,      'color' => 'slate'],
                                'masuk'      => ['label' => 'Masuk',      'count' => $cnt_masuk,      'color' => 'yellow'],
                                'disiapkan'  => ['label' => 'Disiapkan',  'count' => $cnt_disiapkan,  'color' => 'blue'],
                                'selesai'    => ['label' => 'Selesai',    'count' => $cnt_selesai,    'color' => 'emerald'],
                                'dibatalkan' => ['label' => 'Dibatalkan', 'count' => $cnt_dibatalkan, 'color' => 'red'],
                            ];
                            foreach ($tabs as $key => $tab):
                                $isActive = ($filter_status === $key);
                                $activeClass = $isActive
                                    ? 'border-wartan-600 text-wartan-700 font-bold'
                                    : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 font-medium';
                            ?>
                            <a href="<?php echo buildUrl(['status' => $key, 'halaman' => 1]); ?>"
                               class="whitespace-nowrap flex items-center gap-1.5 px-4 py-3 border-b-2 text-sm transition-all <?php echo $activeClass; ?>">
                                <?php echo $tab['label']; ?>
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-xs font-bold
                                    <?php echo $isActive ? 'bg-wartan-100 text-wartan-700' : 'bg-slate-100 text-slate-500'; ?>">
                                    <?php echo $tab['count']; ?>
                                </span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/30">
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">ID Pesanan</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Pemesan & Waktu</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Tipe</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Total Harga</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Status</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php
                                if (mysqli_num_rows($hasil_pesanan) > 0) {
                                    while ($row = mysqli_fetch_assoc($hasil_pesanan)) {
                                ?>
                                        <tr class="hover:bg-slate-50/80 transition-colors group">
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 font-mono">
                                                    #WRT-<?php echo $row['id_pesanan']; ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-100">
                                                        <?php echo substr($row['nama'], 0, 1); ?>
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-slate-800 group-hover:text-wartan-600 transition-colors"><?php echo $row['nama']; ?></div>
                                                        <div class="text-xs text-slate-500 flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            <?php echo date('d/m/Y, H:i', strtotime($row['tanggal_pesanan'])); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php if(isset($row['tipe_pesanan']) && $row['tipe_pesanan'] == 'delivery') { ?>
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                        Delivery
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                                        Pickup
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-slate-800 text-sm">
                                                Rp <?php echo number_format($row['total_pesanan'], 0, ',', '.'); ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php 
                                                if($row['status_pesanan'] == 'masuk') {
                                                    echo '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200"><span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>Masuk</span>';
                                                } else if($row['status_pesanan'] == 'disiapkan') {
                                                    echo '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span>Disiapkan</span>';
                                                } else if($row['status_pesanan'] == 'selesai') {
                                                    echo '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Selesai</span>';
                                                } else {
                                                    echo '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-700 border border-red-200"><span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>Dibatalkan</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="detail_pesanan.php?id=<?php echo $row['id_pesanan']; ?>" class="inline-flex items-center gap-1 bg-white hover:bg-wartan-50 text-wartan-600 border border-wartan-200 hover:border-wartan-500 font-semibold px-4 py-1.5 rounded-lg text-sm transition-all shadow-sm">
                                                    <span>Kelola</span>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                <?php 
                                    } 
                                } else { 
                                ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                Belum ada pesanan masuk.
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Controls -->
                    <?php if ($total_page > 1): ?>
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between gap-4">
                        <p class="text-sm text-slate-500 font-medium">
                            Menampilkan <span class="font-bold text-slate-700"><?php echo $offset + 1; ?></span>–<span class="font-bold text-slate-700"><?php echo min($offset + $per_page, $total_data); ?></span> dari <span class="font-bold text-slate-700"><?php echo $total_data; ?></span> pesanan
                        </p>
                        <div class="flex items-center gap-1">
                            <!-- Prev -->
                            <?php if ($halaman > 1): ?>
                            <a href="<?php echo buildUrl(['halaman' => $halaman - 1]); ?>"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-wartan-50 hover:border-wartan-400 hover:text-wartan-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                            <?php else: ?>
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-slate-100 bg-slate-50 text-slate-300 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php
                            $range = 2;
                            for ($i = 1; $i <= $total_page; $i++):
                                if ($i == 1 || $i == $total_page || ($i >= $halaman - $range && $i <= $halaman + $range)):
                            ?>
                            <a href="<?php echo buildUrl(['halaman' => $i]); ?>"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg border text-sm font-bold transition-all shadow-sm
                               <?php echo ($i == $halaman)
                                    ? 'bg-wartan-600 border-wartan-600 text-white shadow-wartan-200'
                                    : 'border-slate-200 bg-white text-slate-600 hover:bg-wartan-50 hover:border-wartan-400 hover:text-wartan-700'; ?>">
                                <?php echo $i; ?>
                            </a>
                            <?php
                                elseif ($i == $halaman - $range - 1 || $i == $halaman + $range + 1):
                            ?>
                            <span class="inline-flex items-center justify-center w-9 h-9 text-slate-400 text-sm">…</span>
                            <?php
                                endif;
                            endfor;
                            ?>

                            <!-- Next -->
                            <?php if ($halaman < $total_page): ?>
                            <a href="<?php echo buildUrl(['halaman' => $halaman + 1]); ?>"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-wartan-50 hover:border-wartan-400 hover:text-wartan-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <?php else: ?>
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-slate-100 bg-slate-50 text-slate-300 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </main>
    </div>

</body>
</html>