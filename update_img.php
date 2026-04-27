<?php
include 'config/koneksi.php';

$q1 = mysqli_query($koneksi, "UPDATE produk SET gambar='nasigoreng.jfif' WHERE nama_menu LIKE '%Nasi Goreng%'");
$q2 = mysqli_query($koneksi, "UPDATE produk SET gambar='esteh.jfif' WHERE nama_menu LIKE '%Es Teh%'");

if($q1 && $q2) {
    echo "Success updating database.";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
