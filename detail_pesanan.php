<?php
session_start();
if (!isset($_SESSION['status_login'])) {
    header("Location: login.php");
    exit();
}

include 'config/koneksi.php';

// Pastikan ada ID pesanan di URL
if (!isset($_GET['id'])) {
    header("Location: pesanan.php");
    exit();
}

$id_pesanan = $_GET['id'];

// 1. Ambil data utama pesanan
$query_pesanan = "SELECT p.*, u.nama_lengkap as nama, u.alamat_lengkap as alamat_pengiriman 
                  FROM pesanan p 
                  LEFT JOIN user u ON p.id_user = u.id_user 
                  WHERE p.id_pesanan = '$id_pesanan'";
$hasil_pesanan = mysqli_query($koneksi, $query_pesanan);
$data_pesanan = mysqli_fetch_assoc($hasil_pesanan);

if (!$data_pesanan) {
    header("Location: index.php");
    exit();
}

// Verifikasi kepemilikan pesanan
if ($_SESSION['role'] !== 'admin' && $data_pesanan['id_user'] != $_SESSION['id_user']) {
    header("Location: riwayat.php");
    exit();
}

// 2. Ambil detail menu apa saja yang dipesan (JOIN dengan tabel produk untuk dapat nama menu)
$query_detail = "SELECT dp.*, p.nama_menu, p.harga as harga_satuan FROM detail_pesanan dp 
                 LEFT JOIN produk p ON dp.id_produk = p.id_produk 
                 WHERE dp.id_pesanan = '$id_pesanan'";
$hasil_detail = mysqli_query($koneksi, $query_detail);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #WRT-<?php echo $id_pesanan; ?> - Wartan</title>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen flex flex-col">

    <?php include 'components/header.php'; ?>

    <div class="px-4 flex-grow">

    <!-- Main Detail Card -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100">
        
        <!-- Ticket Header -->
        <div class="bg-gradient-to-r from-wartan-600 to-wartan-700 text-white p-8 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight mb-1">Order #WRT-<?php echo $id_pesanan; ?></h2>
                    <p class="text-wartan-50 font-medium opacity-90 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?php echo date('d F Y, H:i', strtotime($data_pesanan['tanggal_pesanan'])); ?>
                    </p>
                </div>
                <div>
                    <div class="flex flex-col items-end gap-3">
                        <div>
                        <?php 
                        if($data_pesanan['status_pesanan'] == 'pending' || $data_pesanan['status_pesanan'] == 'masuk') {
                            echo '<span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-bold bg-white/20 text-white border border-white/30 backdrop-blur-sm"><span class="w-2 h-2 rounded-full bg-yellow-300 animate-pulse"></span>Pesanan Masuk</span>';
                        } else if($data_pesanan['status_pesanan'] == 'diproses') {
                            echo '<span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-bold bg-white/20 text-white border border-white/30 backdrop-blur-sm"><span class="w-2 h-2 rounded-full bg-blue-300 animate-pulse"></span>Sedang Disiapkan</span>';
                        } else if($data_pesanan['status_pesanan'] == 'selesai') {
                            echo '<span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-bold bg-white/20 text-white border border-white/30 backdrop-blur-sm"><span class="w-2 h-2 rounded-full bg-green-300"></span>Selesai</span>';
                        } else {
                            echo '<span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-bold bg-red-500/80 text-white border border-red-400/50 backdrop-blur-sm"><span class="w-2 h-2 rounded-full bg-red-200"></span>Dibatalkan</span>';
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8">
            <!-- Customer Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pelanggan</p>
                    <p class="font-extrabold text-slate-800 text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <?php echo htmlspecialchars($data_pesanan['nama']); ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Metode</p>
                    <p class="font-bold text-slate-800 uppercase flex items-center gap-2">
                        <span class="p-1.5 rounded-md bg-purple-100 text-purple-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></span>
                        ONLINE
                    </p>
                </div>
                <div class="md:col-span-2 lg:col-span-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Detail Alamat / Catatan</p>
                    <p class="font-semibold text-slate-700 bg-white px-3 py-2 rounded-lg border border-slate-200 inline-block w-full">
                        <?php echo htmlspecialchars($data_pesanan['alamat_pengiriman'] ? $data_pesanan['alamat_pengiriman'] : 'Makan di Tempat / Ambil Sendiri'); ?>
                    </p>
                </div>
            </div>

            <h3 class="font-extrabold text-slate-800 mb-4 text-xl flex items-center gap-2">
                <svg class="w-6 h-6 text-wartan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Rincian Pesanan
            </h3>
            
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden mb-8 shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Item Menu</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-24">Qty</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Harga</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php 
                        if (mysqli_num_rows($hasil_detail) > 0) {
                            while ($item = mysqli_fetch_assoc($hasil_detail)) { 
                                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                        ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 font-bold text-slate-800"><?php echo $item['nama_menu'] ? $item['nama_menu'] : '<span class="text-red-500">Menu Dihapus</span>'; ?></td>
                                <td class="p-4 text-center">
                                    <span class="inline-block bg-slate-100 text-slate-700 font-bold px-2.5 py-1 rounded-md text-sm"><?php echo $item['jumlah']; ?>x</span>
                                </td>
                                <td class="p-4 text-right text-slate-600 font-medium">Rp <?php echo number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                                <td class="p-4 text-right font-extrabold text-slate-800">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            </tr>
                        <?php 
                            } 
                        } else {
                            echo '<tr><td colspan="4" class="p-8 text-center text-slate-500 italic">Rincian item belum tersedia (Pesanan Dummy)</td></tr>';
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-wartan-50 border-t-2 border-slate-200">
                            <td colspan="3" class="p-5 text-right font-bold text-slate-600 uppercase tracking-wider text-sm">Total Tagihan:</td>
                            <td class="p-5 text-right font-extrabold text-2xl text-wartan-600 tracking-tight">Rp <?php echo number_format($data_pesanan['total_pesanan'], 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Batalkan Pesanan Full Width Button -->
            <?php if (($_SESSION['role'] !== 'admin') && ($data_pesanan['status_pesanan'] == 'pending' || $data_pesanan['status_pesanan'] == 'masuk')): ?>
            <div class="mt-6 mb-2">
                <a href="actions/batal_pesanan.php?id=<?php echo $id_pesanan; ?>" onclick="event.preventDefault(); const href=this.href; Swal.fire({text: 'Yakin ingin membatalkan pesanan ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Batalkan', cancelButtonText: 'Tidak'}).then((r) => { if(r.isConfirmed) window.location.href=href; })" class="flex items-center justify-center w-full gap-2 px-6 py-4 rounded-2xl text-sm font-bold bg-white hover:bg-red-50 text-red-600 hover:text-red-700 border-2 border-red-100 hover:border-red-200 shadow-sm transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batalkan Pesanan
                </a>
            </div>
            <?php endif; ?>

            <?php if($_SESSION['role'] == 'admin'): ?>
            <!-- Action Area -->
            <div class="bg-slate-800 rounded-2xl p-6 shadow-lg shadow-slate-800/20 text-white">
                <form action="actions/proses_status.php" method="POST" class="flex flex-col md:flex-row items-center gap-6">
                    <input type="hidden" name="id_pesanan" value="<?php echo $id_pesanan; ?>">
                    
                    <div class="flex-1 w-full">
                        <label class="block text-slate-300 font-semibold mb-2 text-sm uppercase tracking-wider">Update Status Proses</label>
                        <div class="relative">
                            <select name="status_pesanan" class="appearance-none w-full bg-slate-700 border border-slate-600 text-white font-bold px-4 py-3.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-wartan-500 focus:border-transparent transition-all cursor-pointer shadow-inner">
                                <option value="masuk" <?php if($data_pesanan['status_pesanan'] == 'masuk') echo 'selected'; ?>>[🟠 Baru] Pesanan Masuk</option>
                                <option value="disiapkan" <?php if($data_pesanan['status_pesanan'] == 'disiapkan') echo 'selected'; ?>>[🔵 Proses] Sedang Disiapkan</option>
                                <option value="selesai" <?php if($data_pesanan['status_pesanan'] == 'selesai') echo 'selected'; ?>>[🟢 Selesai] Siap Diambil / Diantar</option>
                                <option value="dibatalkan" style="color:#fca5a5;" <?php if($data_pesanan['status_pesanan'] == 'dibatalkan') echo 'selected'; ?>>[🔴 Batal] Pesanan Dibatalkan</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" name="update_status" class="w-full md:w-auto mt-6 md:mt-6 bg-wartan-500 hover:bg-wartan-400 text-white font-extrabold py-3.5 px-8 rounded-xl shadow-lg shadow-wartan-500/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </form>
            </div>
            <?php endif; ?>

        </div>
    </div>
    </div>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>
</body>
</html>