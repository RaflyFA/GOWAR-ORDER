<?php
session_start();
include '../config/koneksi.php';

// Proteksi: Pastikan hanya admin yang bisa mengakses file ini
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Pastikan ada ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    // 1. Ambil nama file gambar dari database sebelum datanya dihapus
    $query_gambar = "SELECT gambar FROM produk WHERE id_produk = '$id_produk'";
    $hasil_gambar = mysqli_query($koneksi, $query_gambar);
    $data_gambar  = mysqli_fetch_assoc($hasil_gambar);

    // 2. Hapus file gambar fisiknya dari folder uploads
    if ($data_gambar) {
        $nama_file = $data_gambar['gambar'];
        $lokasi_file = '../assets/uploads/' . $nama_file;
        
        // Cek apakah filenya benar-benar ada di komputer, jika ada maka hapus (unlink)
        if (file_exists($lokasi_file) && $nama_file != '') {
            unlink($lokasi_file);
        }
    }

    // 3. Setelah gambarnya lenyap, barulah hapus data teksnya dari database MySQL
    $query_hapus = "DELETE FROM produk WHERE id_produk = '$id_produk'";
    $eksekusi    = mysqli_query($koneksi, $query_hapus);

    // 4. Beri notifikasi ke admin
    if ($eksekusi) {
        echo "<script>alert('Menu berhasil dihapus!'); window.location.href='../index_admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus menu.'); window.location.href='../index_admin.php';</script>";
    }

} else {
    // Jika tidak ada ID di URL
    header("Location: ../index_admin.php");
}
?>