<?php
// 1. Mulai sesi (WAJIB dipanggil di paling atas agar $_SESSION berfungsi)
session_start();

// 2. Hubungkan ke database (naik satu folder '../' lalu masuk ke folder 'config')
include '../config/koneksi.php';

// 3. Cek apakah tombol "MASUK SEKARANG" (submit_login) benar-benar ditekan
if (isset($_POST['submit_login'])) {
    
    // 4. Tangkap data dari form (atribut 'name' di tag <input>)
    $username_input = $_POST['username'];
    $password_input = $_POST['password'];

    // 5. Buat Query SQL untuk mencari admin di tabel 'user'
    // Kita pastikan role-nya adalah 'admin' agar pelanggan tidak bisa login di halaman ini
    $query = "SELECT * FROM user WHERE username = '$username_input' AND password = '$password_input' AND role = 'admin'";
    
    // 6. Eksekusi query
    $hasil = mysqli_query($koneksi, $query);

    // 7. Cek apakah data ditemukan (jumlah baris > 0)
    if (mysqli_num_rows($hasil) > 0) {
        
        // 8. Ambil data spesifik admin tersebut
        $data_admin = mysqli_fetch_assoc($hasil);

        // 9. Simpan data ke dalam $_SESSION sebagai "Karcis Masuk" (Task 2.3)
        $_SESSION['status_login'] = true;
        $_SESSION['id_admin'] = $data_admin['id_user'];
        $_SESSION['nama_admin'] = $data_admin['nama'];
        $_SESSION['role'] = 'admin';

        // 10. Arahkan (redirect) ke halaman Dashboard Admin
        header("Location: ../dashboard.php");
        exit();

    } else {
        // Jika username atau password salah
        echo "<script>
                alert('Username atau Password Admin salah!');
                window.location.href = '../login.php';
              </script>";
    }
} else {
    // Jika ada yang mencoba mengakses file ini langsung dari URL tanpa mengisi form
    header("Location: ../login.php");
    exit();
}
?>