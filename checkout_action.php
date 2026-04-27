<?php
session_start();
include 'config/koneksi.php';

if(isset($_POST['checkout'])){
    $nama = $_POST['nama_pemesan'];
    $total_harga = $_POST['total_belanja'];
    $tanggal = date('Y-m-d');
    $status = 'masuk';

    // Insert to database
    $query = "INSERT INTO pesanan (nama, total_harga, status_pengiriman, tanggal) VALUES ('$nama', '$total_harga', '$status', '$tanggal')";
    $koneksi->query($query);

    // Clear session keranjang
    unset($_SESSION['keranjang']);

    echo "<script>alert('Pesanan berhasil dibuat! Makanan Anda sedang diproses dan masuk ke antrean dapur kami.');</script>";
    echo "<script>location='index.php';</script>";
}
?>
