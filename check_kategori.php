<?php
include 'config/koneksi.php';
$q = mysqli_query($koneksi, "SELECT id_produk, nama_menu, kategori_produk FROM produk");
while($row = mysqli_fetch_assoc($q)) {
    echo $row['nama_menu'] . " => " . $row['kategori_produk'] . "\n";
}
?>
