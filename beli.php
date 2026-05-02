<?php
session_start();

if (!isset($_SESSION['status_login'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Silakan login terlebih dahulu untuk memesan menu.',
        icon: 'warning',
        confirmButtonColor: '#1a8f50'
    });
</script></body>";
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

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Produk telah masuk ke keranjang belanja',
        icon: 'success',
        confirmButtonColor: '#1a8f50'
    });
</script></body>";
echo "<script>location='keranjang.php';</script>";
?>