<?php
session_start();
include '../config/koneksi.php';

// Pastikan tombol submit benar-benar ditekan
if (isset($_POST['submit_tambah'])) {
    
    // 1. Tangkap data teks dari form
    $nama_menu = $_POST['nama_menu'];
    $deskripsi = $_POST['deskripsi'];
    $harga     = $_POST['harga'];
    $kategori  = $_POST['kategori_produk'];
    $status    = 'tersedia'; // Status default saat baru ditambah pasti "tersedia"

    // 2. Tangkap data file gambar yang diupload
    $nama_file_asli = $_FILES['gambar']['name'];
    $ukuran_file    = $_FILES['gambar']['size'];
    $tmp_file       = $_FILES['gambar']['tmp_name']; // Ini lokasi sementara file di komputer

    // 3. Kita acak nama filenya agar tidak bentrok jika admin mengupload gambar dengan nama yang sama
    // Misalnya: "ayam.jpg" berubah jadi "menu_64d2a1b3.jpg"
    $ekstensi_file  = pathinfo($nama_file_asli, PATHINFO_EXTENSION);
    $nama_file_baru = 'menu_' . uniqid() . '.' . $ekstensi_file;

    // 4. Tentukan lokasi folder tempat gambar akan menetap (Folder assets/uploads yang baru kita buat)
    $lokasi_simpan = '../assets/uploads/' . $nama_file_baru;

    // 5. Cek ukuran file (Maksimal 2MB agar website tidak berat)
    if ($ukuran_file > 2000000) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Ukuran gambar terlalu besar! Maksimal 2MB.',
        icon: 'error',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../tambah_menu.php';
    });
</script></body>";
        exit();
    }

    // 6. Pindahkan file dari penyimpanan sementara (tmp) ke folder tujuan kita
    if (move_uploaded_file($tmp_file, $lokasi_simpan)) {
        
        // 7. Jika gambar sukses dipindah, simpan datanya (termasuk nama file baru) ke database MySQL
        $query = "INSERT INTO produk (nama_menu, deskripsi, harga, gambar, kategori_produk, status) 
                  VALUES ('$nama_menu', '$deskripsi', '$harga', '$nama_file_baru', '$kategori', '$status')";
        
        $eksekusi = mysqli_query($koneksi, $query);

        // 8. Cek apakah query INSERT berhasil
        if ($eksekusi) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Berhasil! Menu baru sudah ditambahkan.',
        icon: 'success',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../index_admin.php';
    });
</script></body>";
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Gagal menyimpan ke database.',
        icon: 'error',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../tambah_menu.php';
    });
</script></body>";
        }

    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<body style='background-color: #f8fafc; font-family: sans-serif;'>
<script>
    Swal.fire({
        text: 'Gagal mengupload gambar.',
        icon: 'error',
        confirmButtonColor: '#1a8f50'
    }).then(() => {
        window.location.href='../tambah_menu.php';
    });
</script></body>";
    }

} else {
    header("Location: ../index_admin.php");
}
?>