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
        echo "<script>alert('Username sudah digunakan! Silakan pilih yang lain.'); window.location.href='../daftar.php';</script>";
        exit();
    }

    $query = "INSERT INTO user (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama_lengkap', '$role')";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href='../login.php';</script>";
    } else {
        echo "<script>alert('Pendaftaran gagal. Silakan coba lagi.'); window.location.href='../daftar.php';</script>";
    }
} else {
    header("Location: ../daftar.php");
}
?>
