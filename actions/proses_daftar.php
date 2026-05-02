<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['submit_daftar'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username     = $_POST['username'];
    $password     = $_POST['password']; // Sebaiknya di-hash untuk keamanan, misal password_hash()
    $role         = 'user';

    // Cek apakah username sudah ada
    $cek_username = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
    if(mysqli_num_rows($cek_username) > 0) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Username sudah digunakan! Silakan pilih yang lain.',
        icon: 'error',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../daftar.php';
    });
</script></body>";
        exit();
    }

    $query = "INSERT INTO user (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama_lengkap', '$role')";
    if (mysqli_query($koneksi, $query)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Pendaftaran berhasil! Silakan login.',
        icon: 'success',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../login.php';
    });
</script></body>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Pendaftaran gagal. Silakan coba lagi.',
        icon: 'error',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../daftar.php';
    });
</script></body>";
    }
} else {
    header("Location: ../daftar.php");
}
?>
