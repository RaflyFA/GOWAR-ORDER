<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['update_status'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status_pengiriman'];

    // Update status di database
    $query = "UPDATE pesanan SET status_pengiriman = '$status_baru' WHERE id_pesanan = '$id_pesanan'";
    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        // Kembali ke halaman pesanan utama
        header("Location: ../pesanan.php");
    } else {
        echo "<script>alert('Gagal merubah status!'); window.location.href='../detail_pesanan.php?id=$id_pesanan';</script>";
    }
} else {
    header("Location: ../pesanan.php");
}
?>