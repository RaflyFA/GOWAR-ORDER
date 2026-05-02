<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['update_status'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status_pesanan'];

    // Update status di database
    $query = "UPDATE pesanan SET status_pesanan = '$status_baru' WHERE id_pesanan = '$id_pesanan'";
    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        // Kembali ke halaman pesanan utama
        header("Location: ../pesanan.php");
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Gagal merubah status!',
        icon: 'error',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../detail_pesanan.php?id=$id_pesanan';
    });
</script></body>";
    }
} else {
    header("Location: ../pesanan.php");
}
?>