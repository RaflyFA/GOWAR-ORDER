<?php
session_start();
include '../config/koneksi.php';

// Pastikan tombol "Simpan Perubahan" benar-benar ditekan
if (isset($_POST['submit_edit'])) {
    
    // 1. Tangkap semua data teks dari form
    $id_produk   = $_POST['id_produk'];
    $nama_menu   = $_POST['nama_menu'];
    $deskripsi   = $_POST['deskripsi'];
    $harga       = $_POST['harga'];
    $status      = $_POST['status'];
    
    // Ini nama gambar yang lama, ditangkap dari input hidden
    $gambar_lama = $_POST['gambar_lama'];

    // 2. Cek apakah admin mengupload gambar baru
    // Angka 4 pada error handling $_FILES artinya "Tidak ada file yang diupload"
    if ($_FILES['gambar_baru']['error'] === 4) {
        
        // Skenario A: Admin TIDAK mengganti gambar
        $gambar_final = $gambar_lama;
        
    } else {
        
        // Skenario B: Admin MENGGANTI gambar dengan yang baru
        $nama_file_asli = $_FILES['gambar_baru']['name'];
        $ukuran_file    = $_FILES['gambar_baru']['size'];
        $tmp_file       = $_FILES['gambar_baru']['tmp_name'];

        // Acak nama file baru seperti saat proses tambah menu
        $ekstensi_file  = pathinfo($nama_file_asli, PATHINFO_EXTENSION);
        $nama_file_baru = 'menu_' . uniqid() . '.' . $ekstensi_file;
        $lokasi_simpan  = '../assets/uploads/' . $nama_file_baru;

        // Cek ukuran file
        if ($ukuran_file > 2000000) {
            echo "<script>alert('Ukuran gambar terlalu besar! Maksimal 2MB.'); window.location.href='../edit_menu.php?id=$id_produk';</script>";
            exit();
        }

        // Pindahkan file gambar yang baru ke folder uploads
        if (move_uploaded_file($tmp_file, $lokasi_simpan)) {
            $gambar_final = $nama_file_baru;
            
            // Best Practice: Hapus file gambar lama dari komputer/server agar hardisk tidak penuh
            $lokasi_gambar_lama = '../assets/uploads/' . $gambar_lama;
            if (file_exists($lokasi_gambar_lama) && $gambar_lama != '') {
                unlink($lokasi_gambar_lama); // Perintah hapus file
            }
        } else {
            echo "<script>alert('Gagal mengupload gambar baru.'); window.location.href='../edit_menu.php?id=$id_produk';</script>";
            exit();
        }
    }

    // 3. Simpan perubahan ke database MySQL menggunakan perintah UPDATE
    $query = "UPDATE produk SET 
                nama_menu = '$nama_menu', 
                deskripsi = '$deskripsi', 
                harga = '$harga', 
                status = '$status', 
                gambar = '$gambar_final' 
              WHERE id_produk = '$id_produk'";
    
    $eksekusi = mysqli_query($koneksi, $query);

    // 4. Cek apakah query UPDATE berhasil
    if ($eksekusi) {
        echo "<script>alert('Berhasil! Menu sudah diperbarui.'); window.location.href='../index_admin.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate database.'); window.location.href='../edit_menu.php?id=$id_produk';</script>";
    }

} else {
    // Jika file diakses langsung tanpa lewat form
    header("Location: ../index_admin.php");
}
?>