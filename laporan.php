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
    // Membuat nama file otomatis tanpa spasi. Contoh: Laporan_Wartan_Minggu_Ini
    $nama_file_cetak = "Laporan_Wartan_" . str_replace(' ', '_', $periode_teks); 
    ?>
    <title><?php echo $nama_file_cetak; ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        .bg-wartan { background-color: #1a8f50; }
        @media print {
            .no-print { display: none !important; }
            .print-area { width: 100% !important; padding: 0 !important; margin: 0 !important; }
            body { background-color: white; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-wartan text-white p-4 shadow-md flex justify-between items-center no-print">
        <h1 class="text-xl font-bold">Wartan Admin</h1>
        <div class="flex gap-4">
            <button onclick="cetakLaporan()" class="bg-white text-green-700 px-4 py-1 rounded text-sm font-bold flex items-center gap-2 hover:bg-gray-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak
            </button>
            <button onclick="downloadPDF()" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-4 py-1 rounded text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download PDF
            </button>
            <a href="actions/logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition">Logout</a>
        </div>
    </nav>

    <script>
        // Nama file otomatis dari PHP
        var namaFile = "<?php echo $nama_file_cetak; ?>";

        function cetakLaporan() {
            // Set judul dokumen = nama file, lalu print
            var judulAsli = document.title;
            document.title = namaFile;
            window.print();
            document.title = judulAsli;
        }

        function downloadPDF() {
            var tombol = document.querySelectorAll('.no-print');
            var element = document.querySelector('.print-area');
            var opt = {
                margin:       0.5,
                filename:     namaFile + '.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>

    <div class="flex h-[calc(100vh-60px)]">
        
        <div class="w-64 bg-white shadow-lg p-4 no-print">
            <ul class="space-y-2">
                <li><a href="index_admin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Dashboard</a></li>
                <li><a href="index_admin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Kelola Menu</a></li>
                <li><a href="pesanan.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Pesanan Masuk</a></li>
                <li><a href="laporan.php" class="block px-4 py-2 bg-wartan text-white rounded font-semibold">Laporan</a></li>
            </ul>
        </div>

        <div class="flex-1 p-8 overflow-y-auto print-area">
            
            <div class="mb-6 flex items-center gap-3 no-print">
                <label class="font-bold text-gray-700">Filter:</label>
                <form action="laporan.php" method="GET" class="flex gap-2">
                    <select name="filter" class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:border-green-500">
                        <option value="semua" <?php if($filter=='semua') echo 'selected'; ?>>Semua Data</option>
                        <option value="hari_ini" <?php if($filter=='hari_ini') echo 'selected'; ?>>Hari Ini</option>
                        <option value="minggu_ini" <?php if($filter=='minggu_ini') echo 'selected'; ?>>Minggu Ini</option>
                        <option value="bulan_ini" <?php if($filter=='bulan_ini') echo 'selected'; ?>>Bulan Ini</option>
                    </select>
                    <button type="submit" class="bg-wartan text-white px-4 py-1 rounded hover:bg-green-700 transition">Tampilkan</button>
                </form>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Laporan Transaksi - Warung Tanjakan</h2>
                <p class="text-gray-500">Periode: <?php echo $periode_teks; ?></p>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Pelanggan</th>
                            <th class="p-3">Tipe</th>
                            <th class="p-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        if (mysqli_num_rows($hasil_laporan) > 0) {
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($hasil_laporan)) {
                                // Tambahkan harga ke total omzet
                                $total_omzet += $row['total_harga'];
                        ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3"><?php echo $no++; ?></td>
                                    <td class="p-3"><?php echo date('d/m/y H:i', strtotime($row['tanggal'])); ?></td>
                                    <td class="p-3"><?php echo $row['nama']; ?></td>
                                    <td class="p-3"><?php echo ucfirst($row['tipe_pesanan']); ?></td>
                                    <td class="p-3 text-right">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                </tr>
                        <?php 
                            } 
                        } else { 
                            echo '<tr><td colspan="5" class="p-6 text-center text-gray-500">Tidak ada transaksi Selesai pada periode ini.</td></tr>';
                        } 
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 font-bold text-lg border-t-2 border-gray-300">
                            <td colspan="4" class="p-4 text-right">TOTAL OMZET:</td>
                            <td class="p-4 text-right text-green-600">Rp <?php echo number_format($total_omzet, 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</body>
</html>