<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];
    $id_user = $_SESSION['id_user'];

    // Verify ownership
    $cek = mysqli_query($koneksi, "SELECT id_pesanan FROM pesanan WHERE id_pesanan = '$id_pesanan' AND id_user = '$id_user'");
    if (mysqli_num_rows($cek) > 0) {
        $query = "UPDATE pesanan SET status_pesanan = 'dibatalkan' WHERE id_pesanan = '$id_pesanan'";
        $eksekusi = mysqli_query($koneksi, $query);

        if ($eksekusi) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Pesanan berhasil dibatalkan!',
        icon: 'success',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../detail_pesanan.php?id=$id_pesanan';
    });
</script></body>";
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Gagal membatalkan pesanan!',
        icon: 'error',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../detail_pesanan.php?id=$id_pesanan';
    });
</script></body>";
        }
    } else {
        header("Location: ../riwayat.php");
    }
} else {
    header("Location: ../riwayat.php");
}
?>
