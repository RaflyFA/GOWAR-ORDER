<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
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
$query_pesanan = "SELECT * FROM pesanan WHERE id_pesanan = '$id_pesanan'";
$hasil_pesanan = mysqli_query($koneksi, $query_pesanan);
$data_pesanan = mysqli_fetch_assoc($hasil_pesanan);

if (!$data_pesanan) {
    header("Location: pesanan.php");
    exit();
}

// 2. Ambil detail menu apa saja yang dipesan (JOIN dengan tabel produk untuk dapat nama menu)
$query_detail = "SELECT dp.*, p.nama_menu FROM detail_pesanan dp 
                 LEFT JOIN produk p ON dp.id_produk = p.id_produk 
                 WHERE dp.id_pesanan = '$id_pesanan'";
$hasil_detail = mysqli_query($koneksi, $query_detail);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan #WRT-<?php echo $id_pesanan; ?> - Wartan Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>.bg-wartan { background-color: #1a8f50; }</style>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        
        <div class="bg-wartan text-white p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold">Detail Pesanan #WRT-<?php echo $id_pesanan; ?></h2>
            <a href="pesanan.php" class="bg-white text-green-700 px-3 py-1 rounded text-sm font-semibold hover:bg-gray-100">Kembali</a>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div>
                    <p class="text-sm text-gray-500">Nama Pemesan</p>
                    <p class="font-bold text-gray-800"><?php echo $data_pesanan['nama']; ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Waktu Pemesanan</p>
                    <p class="font-bold text-gray-800"><?php echo date('d/m/Y H:i', strtotime($data_pesanan['tanggal'])); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tipe Pesanan</p>
                    <p class="font-bold text-gray-800 uppercase"><?php echo $data_pesanan['tipe_pesanan']; ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Alamat Pengiriman</p>
                    <p class="font-bold text-gray-800"><?php echo $data_pesanan['alamat_pengiriman'] ? $data_pesanan['alamat_pengiriman'] : '- (Ambil di tempat)'; ?></p>
                </div>
            </div>

            <h3 class="font-bold text-gray-700 mb-3">Item yang Dipesan:</h3>
            <table class="w-full text-left mb-6 border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-2">Nama Menu</th>
                        <th class="p-2 text-center">Jumlah</th>
                        <th class="p-2 text-right">Harga Satuan</th>
                        <th class="p-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($hasil_detail) > 0) {
                        while ($item = mysqli_fetch_assoc($hasil_detail)) { 
                            $subtotal = $item['jumlah'] * $item['harga_satuan'];
                    ?>
                        <tr class="border-b">
                            <td class="p-2"><?php echo $item['nama_menu'] ? $item['nama_menu'] : 'Menu Dihapus'; ?></td>
                            <td class="p-2 text-center"><?php echo $item['jumlah']; ?>x</td>
                            <td class="p-2 text-right">Rp <?php echo number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                            <td class="p-2 text-right font-semibold">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                        </tr>
                    <?php 
                        } 
                    } else {
                        echo '<tr><td colspan="4" class="p-4 text-center text-gray-500 italic">Rincian item belum tersedia (Pesanan Dummy)</td></tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold text-lg">
                        <td colspan="3" class="p-3 text-right">Total Pembayaran:</td>
                        <td class="p-3 text-right text-green-700">Rp <?php echo number_format($data_pesanan['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>

            <div class="border-t pt-6">
                <form action="actions/proses_status.php" method="POST" class="flex items-end gap-4">
                    <input type="hidden" name="id_pesanan" value="<?php echo $id_pesanan; ?>">
                    
                    <div class="flex-1">
                        <label class="block text-gray-700 font-bold mb-2">Update Status Pesanan:</label>
                        <select name="status_pengiriman" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="masuk" <?php if($data_pesanan['status_pengiriman'] == 'masuk') echo 'selected'; ?>>Masuk (Baru)</option>
                            <option value="disiapkan" <?php if($data_pesanan['status_pengiriman'] == 'disiapkan') echo 'selected'; ?>>Disiapkan (Sedang Dimasak)</option>
                            <option value="selesai" <?php if($data_pesanan['status_pengiriman'] == 'selesai') echo 'selected'; ?>>Selesai (Siap Diambil/Diantar)</option>
                        </select>
                    </div>
                    <button type="submit" name="update_status" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                        Simpan Status
                    </button>
                </form>
            </div>

        </div>
    </div>
</body>
</html>