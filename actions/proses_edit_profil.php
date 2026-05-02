<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status_login'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['simpan_profil'])) {
    $id_user = $_SESSION['id_user'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $alamat_lengkap = $_POST['alamat_lengkap'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Update session nama in case it changed
    $_SESSION['nama_admin'] = $nama_lengkap;

    // Update query
    $query = "UPDATE user SET 
                nama_lengkap = '$nama_lengkap',
                email = '$email',
                whatsapp = '$whatsapp',
                alamat_lengkap = '$alamat_lengkap',
                latitude = '$latitude',
                longitude = '$longitude'
              WHERE id_user = '$id_user'";
              
    if (mysqli_query($koneksi, $query)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Profil berhasil diperbarui!',
        icon: 'success',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../profil.php';
    });
</script></body>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Gagal memperbarui profil. Silakan coba lagi.',
        icon: 'error',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../profil.php';
    });
</script></body>";
    }
} else {
    header("Location: ../profil.php");
}
?>
