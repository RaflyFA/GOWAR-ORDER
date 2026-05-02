<?php
session_start();
$id_produk = $_GET['id'];
unset($_SESSION['keranjang'][$id_produk]);

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Menu dihapus dari keranjang',
        icon: 'info',
        confirmButtonColor: '#1a8f50'
    });
</script></body>";
echo "<script>location='keranjang.php';</script>";
?>
