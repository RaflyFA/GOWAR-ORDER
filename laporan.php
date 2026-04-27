<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'config/koneksi.php';

// 1. Logika Filter Waktu
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua';

// Kita HANYA menghitung pesanan yang statusnya sudah 'selesai'
$where_clause = "WHERE status_pengiriman = 'selesai'"; 
$periode_teks = "Semua Waktu";

if ($filter == 'hari_ini') {
    $where_clause .= " AND DATE(tanggal) = CURDATE()";
    $periode_teks = "Hari Ini";
} else if ($filter == 'minggu_ini') {
    // YEARWEEK dengan parameter 1 (Senin sebagai awal minggu)
    $where_clause .= " AND YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)";
    $periode_teks = "Minggu Ini";
} else if ($filter == 'bulan_ini') {
    $where_clause .= " AND MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())";
    $periode_teks = "Bulan Ini";
}

// 2. Ambil data pesanan sesuai filter
$query_laporan = "SELECT * FROM pesanan $where_clause ORDER BY tanggal DESC";
$hasil_laporan = mysqli_query($koneksi, $query_laporan);

// Variabel untuk menampung total uang
$total_omzet = 0; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <?php 
    // Membuat nama file otomatis tanpa spasi
    $nama_file_cetak = "Laporan_Wartan_" . str_replace(' ', '_', $periode_teks); 
    ?>
    <title><?php echo $nama_file_cetak; ?></title>
    <!-- Modern Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
        
        /* Print Styles */
        @media print {
            .no-print { display: none !important; }
            .print-area { width: 100% !important; padding: 0 !important; margin: 0 !important; }
            body { background-color: white !important; }
            main { height: auto !important; overflow: visible !important; }
            .print-shadow-none { box-shadow: none !important; border-color: #e2e8f0 !important; }
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-wartan-500 selection:text-white">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Modern Sidebar (no-print) -->
        <aside class="w-72 bg-white border-r border-slate-200 flex flex-col transition-all duration-300 z-20 no-print">
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
                        <a href="pesanan.php" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-wartan-600 rounded-xl font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Pesanan Masuk
                        </a>
                    </li>
                    <li>
                        <a href="laporan.php" class="flex items-center gap-3 px-4 py-3 bg-wartan-600 text-white rounded-xl font-semibold shadow-md shadow-green-500/20 transition-all">
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
        <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-slate-50 relative">
            
            <!-- Top Header (no-print) -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 z-10 sticky top-0 no-print">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Laporan Penjualan</h1>
                    <p class="text-sm text-slate-500">Rekap transaksi Selesai berdasarkan periode waktu tertentu.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="cetakLaporan()" class="bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:text-wartan-600 px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print Laporan
                    </button>
                    <button onclick="downloadPDF()" class="bg-amber-400 hover:bg-amber-500 text-amber-900 px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download PDF
                    </button>
                </div>
            </header>

            <!-- Scrollable Content / Print Area -->
            <div class="p-8 max-w-5xl mx-auto w-full">
                
                <!-- Filter Form (no-print) -->
                <div class="mb-8 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between no-print">
                    <div class="flex items-center gap-2 text-slate-700">
                        <svg class="w-5 h-5 text-wartan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <span class="font-bold">Filter Data:</span>
                    </div>
                    <form action="laporan.php" method="GET" class="flex gap-3">
                        <div class="relative">
                            <select name="filter" class="appearance-none bg-slate-50 border border-slate-200 text-slate-700 font-medium rounded-xl pl-4 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-wartan-500/20 focus:border-wartan-500 transition-all cursor-pointer">
                                <option value="semua" <?php if($filter=='semua') echo 'selected'; ?>>Semua Waktu</option>
                                <option value="hari_ini" <?php if($filter=='hari_ini') echo 'selected'; ?>>Hari Ini</option>
                                <option value="minggu_ini" <?php if($filter=='minggu_ini') echo 'selected'; ?>>Minggu Ini</option>
                                <option value="bulan_ini" <?php if($filter=='bulan_ini') echo 'selected'; ?>>Bulan Ini</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <button type="submit" class="bg-wartan-600 text-white font-bold px-5 py-2 rounded-xl hover:bg-wartan-700 transition-colors shadow-sm">Terapkan</button>
                    </form>
                </div>

                <!-- Laporan / PDF Content Area -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm print-shadow-none overflow-hidden print-area">
                    
                    <!-- Report Header -->
                    <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">Laporan Penjualan</h2>
                            <p class="text-slate-500 font-medium">Warung Makan Tanjakan (Wartan)</p>
                        </div>
                        <div class="text-right">
                            <div class="inline-flex items-center gap-1.5 bg-white border border-slate-200 px-3 py-1 rounded-lg text-sm font-bold text-slate-700 mb-2">
                                <svg class="w-4 h-4 text-wartan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Periode: <?php echo $periode_teks; ?>
                            </div>
                            <p class="text-xs text-slate-400">Dicetak: <?php echo date('d/m/Y H:i'); ?></p>
                        </div>
                    </div>

                    <!-- Table -->
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider font-bold">
                                <th class="px-6 py-4 border-y border-slate-200">No</th>
                                <th class="px-6 py-4 border-y border-slate-200">Tanggal</th>
                                <th class="px-6 py-4 border-y border-slate-200">Pelanggan</th>
                                <th class="px-6 py-4 border-y border-slate-200">Tipe</th>
                                <th class="px-6 py-4 border-y border-slate-200 text-right">Total Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php
                            if (mysqli_num_rows($hasil_laporan) > 0) {
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($hasil_laporan)) {
                                    $total_omzet += $row['total_harga'];
                            ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 text-sm font-medium text-slate-500"><?php echo $no++; ?></td>
                                        <td class="px-6 py-4 text-sm"><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                        <td class="px-6 py-4 font-semibold text-slate-800"><?php echo $row['nama']; ?></td>
                                        <td class="px-6 py-4">
                                            <?php if($row['tipe_pesanan'] == 'delivery') { ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">Delivery</span>
                                            <?php } else { ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-50 text-orange-700 border border-orange-100">Pickup</span>
                                            <?php } ?>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-800">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    </tr>
                            <?php 
                                } 
                            } else { 
                            ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        Tidak ada transaksi Selesai pada periode <strong><?php echo $periode_teks; ?></strong>.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-wartan-50 border-t border-slate-200">
                                <td colspan="4" class="px-6 py-5 text-right text-sm font-bold text-slate-600 uppercase tracking-wider">
                                    Total Pendapatan Bersih:
                                </td>
                                <td class="px-6 py-5 text-right text-xl font-extrabold text-wartan-600">
                                    Rp <?php echo number_format($total_omzet, 0, ',', '.'); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </main>
    </div>

    <!-- Scripts for PDF & Print -->
    <script>
        var namaFile = "<?php echo $nama_file_cetak; ?>";

        function cetakLaporan() {
            var judulAsli = document.title;
            document.title = namaFile;
            window.print();
            document.title = judulAsli;
        }

        function downloadPDF() {
            var element = document.querySelector('.print-area');
            var opt = {
                margin:       0.5,
                filename:     namaFile + '.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>