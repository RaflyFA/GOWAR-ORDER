<?php
session_start();
include 'config/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Silakan login terlebih dahulu',
        icon: 'warning',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='login.php';
    });
</script></body>";
    exit;
}

$id_user = $_SESSION['id_user'];

// Hapus detail pesanan terlebih dahulu (jika ada foreign key constraint)
$query_detail = "DELETE dp FROM detail_pesanan dp
                 INNER JOIN pesanan p ON dp.id_pesanan = p.id_pesanan
                 WHERE p.id_user = '$id_user'";
mysqli_query($koneksi, $query_detail);

// Hapus data pesanan
$query_pesanan = "DELETE FROM pesanan WHERE id_user = '$id_user'";
mysqli_query($koneksi, $query_pesanan);

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Semua riwayat pesanan berhasil dihapus',
        icon: 'success',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='riwayat.php';
    });
</script></body>";
?>
