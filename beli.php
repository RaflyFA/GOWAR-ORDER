<?php
session_start();

if (!isset($_SESSION['status_login'])) {
    echo "<script>alert('Silakan login terlebih dahulu untuk memesan menu.');</script>";
    echo "<script>location='login.php';</script>";
    exit();
}

// Ambil ID produk dari URL
$id_produk = $_GET['id'];

// Jika produk sudah ada di keranjang, jumlahnya tambah 1
if(isset($_SESSION['keranjang'][$id_produk])) {
    $_SESSION['keranjang'][$id_produk] += 1;
} 
// Jika belum ada, set jumlah jadi 1
else {
    $_SESSION['keranjang'][$id_produk] = 1;
}

echo "<script>alert('Produk telah masuk ke keranjang belanja');</script>";
echo "<script>location='keranjang.php';</script>";
?>