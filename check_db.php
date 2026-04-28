<?php
include 'config/koneksi.php';

$q = mysqli_query($koneksi, "SELECT id_produk, nama_menu, gambar FROM produk");
while($row = mysqli_fetch_assoc($q)) {
    echo $row['id_produk'] . " - " . $row['nama_menu'] . " - " . $row['gambar'] . "\n";
}
?>
