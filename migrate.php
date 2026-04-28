<?php
include 'config/koneksi.php';

// 1. Tambah kolom kategori_produk ke tabel produk
$koneksi->query("ALTER TABLE produk ADD COLUMN kategori_produk ENUM('Makanan', 'Minuman') DEFAULT 'Makanan'");
echo "Added kategori_produk\n";

// 2. Sesuaikan nama kolom agar cocok dengan script teman user (index_admin, proses_tambah_menu)
// Cek jika kolom lama masih ada
$check = $koneksi->query("SHOW COLUMNS FROM produk LIKE 'nama_produk'");
if($check && $check->num_rows > 0) {
    $koneksi->query("ALTER TABLE produk CHANGE nama_produk nama_menu varchar(100)");
    $koneksi->query("ALTER TABLE produk CHANGE harga_produk harga int");
    $koneksi->query("ALTER TABLE produk CHANGE foto_produk gambar varchar(100)");
    $koneksi->query("ALTER TABLE produk CHANGE deskripsi_produk deskripsi text");
    $koneksi->query("ALTER TABLE produk ADD COLUMN status ENUM('tersedia', 'habis') DEFAULT 'tersedia'");
    echo "Renamed columns to match friend's code\n";
} else {
    echo "Columns already match friend's code\n";
}

$result = $koneksi->query("DESCRIBE produk");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
?>
