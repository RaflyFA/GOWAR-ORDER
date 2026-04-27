<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'config/koneksi.php';

// Ambil ID dari URL (contoh: edit_menu.php?id=1)
if (!isset($_GET['id'])) {
    header("Location: index_admin.php");
    exit();
}

$id_produk = $_GET['id'];

// Cari data menu tersebut di database
$query = "SELECT * FROM produk WHERE id_produk = '$id_produk'";
$hasil = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($hasil);

// Jika admin iseng masukin ID ngawur di URL, kembalikan ke dashboard
if (!$data) {
    header("Location: index_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu - Wartan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>.bg-wartan { background-color: #1a8f50; }</style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-10">

    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Menu Makanan</h2>
        </div>

        <form action="actions/proses_edit_menu.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_produk" value="<?php echo $data['id_produk']; ?>">
            <input type="hidden" name="gambar_lama" value="<?php echo $data['gambar']; ?>">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Nama Menu</label>
                <input type="text" name="nama_menu" value="<?php echo $data['nama_menu']; ?>" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"><?php echo $data['deskripsi']; ?></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Harga (Rp)</label>
                <input type="number" name="harga" value="<?php echo $data['harga']; ?>" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Status Ketersediaan</label>
                <select name="status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="tersedia" <?php if($data['status'] == 'tersedia') echo 'selected'; ?>>Tersedia</option>
                    <option value="habis" <?php if($data['status'] == 'habis') echo 'selected'; ?>>Habis</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Ganti Gambar (Opsional)</label>
                <div class="mb-2">
                    <span class="text-xs text-gray-500">Gambar saat ini: <?php echo $data['gambar']; ?></span>
                </div>
                <input type="file" name="gambar_baru" accept="image/*" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti gambar.</p>
            </div>

            <div class="flex justify-end gap-3">
                <a href="index_admin.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Batal</a>
                <button type="submit" name="submit_edit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</body>
</html>