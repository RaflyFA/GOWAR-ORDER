<?php
// Deklarasi variabel untuk koneksi ke database lokal
$host     = "localhost";
$username = "root";       // Default username untuk XAMPP/Laragon
$password = "";           // Kosongkan jika password root Laragon Anda belum pernah diubah
$database = "db_wartan";  // Nama database yang baru saja kita buat di HeidiSQL

// Membuat koneksi menggunakan ekstensi mysqli
$koneksi = mysqli_connect($host, $username, $password, $database);

// Mengecek apakah koneksi berhasil atau gagal
if (!$koneksi) {
    // Jika gagal, hentikan program dan tampilkan pesan error
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Jika berhasil, Anda bisa menghapus (atau menjadikan komentar) baris echo di bawah ini nanti
// echo "Koneksi ke database db_wartan berhasil!";
?>