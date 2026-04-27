<?php
include 'config/koneksi.php';
mysqli_query($koneksi, "UPDATE produk SET gambar='nasigoreng.jpg' WHERE gambar='nasigoreng.jfif'");
mysqli_query($koneksi, "UPDATE produk SET gambar='esteh.jpg' WHERE gambar='esteh.jfif'");
echo "Database updated to use .jpg extensions.";
?>
