<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'config/koneksi.php';

// Ambil ID dari URL (contoh: edit_menu.php?id=1)
if (!isset($_GET['id'])) {
    header("Location: index_admin.php");
    exit();
}

$id_produk = $_GET['id'];

// Cari data menu tersebut di database
$query = "SELECT * FROM produk WHERE id_produk = '$id_produk'";
$hasil = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($hasil);

// Jika admin iseng masukin ID ngawur di URL, kembalikan ke dashboard
if (!$data) {
    header("Location: index_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - Gowar Admin</title>
    <!-- Modern Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        wartan: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#1a8f50',
                            700: '#15803d',
                            800: '#147340',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 flex items-center justify-center min-h-screen p-4 selection:bg-wartan-500 selection:text-white relative overflow-hidden">
    
    <!-- Background Decoration -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-amber-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    </div>

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 w-full max-w-xl border border-white p-8 md:p-10 transition-all hover:shadow-2xl hover:shadow-slate-200/60 relative my-8">
        
        <div class="absolute top-0 right-0 p-8">
            <a href="index_admin.php" class="text-slate-400 hover:text-red-500 hover:rotate-90 transition-all duration-300 block bg-slate-50 hover:bg-red-50 p-2 rounded-xl border border-slate-100 hover:border-red-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>

        <div class="mb-8 pr-12">
            <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mb-4 border border-amber-100">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Edit Menu</h2>
            <p class="text-slate-500 mt-1 font-medium">Perbarui informasi, harga, atau ketersediaan menu masakan.</p>
        </div>

        <form action="actions/proses_edit_menu.php" method="POST" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="id_produk" value="<?php echo $data['id_produk']; ?>">
            <input type="hidden" name="gambar_lama" value="<?php echo $data['gambar']; ?>">
            
            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-slate-700">Nama Menu</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <input type="text" name="nama_menu" value="<?php echo $data['nama_menu']; ?>" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all shadow-sm font-medium" required>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-slate-700">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all shadow-sm font-medium resize-none"><?php echo $data['deskripsi']; ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Harga (Rp)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold">Rp</div>
                        <input type="number" name="harga" value="<?php echo $data['harga']; ?>" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all shadow-sm font-bold text-lg" required>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Status Ketersediaan</label>
                    <div class="relative">
                        <select name="status" class="appearance-none w-full px-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all cursor-pointer font-bold shadow-sm">
                            <option value="tersedia" <?php if($data['status'] == 'tersedia') echo 'selected'; ?>>✅ Tersedia</option>
                            <option value="habis" <?php if($data['status'] == 'habis') echo 'selected'; ?>>❌ Habis</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <label class="block text-sm font-bold text-slate-700">Ganti Foto (Opsional)</label>
                
                <?php if(!empty($data['gambar'])): ?>
                <div class="flex items-center gap-3 mb-3 p-3 bg-white border border-slate-100 rounded-lg shadow-sm">
                    <img src="assets/uploads/<?php echo $data['gambar']; ?>" alt="Current" class="w-12 h-12 object-cover rounded-md border border-slate-200">
                    <div class="text-xs text-slate-500 overflow-hidden text-ellipsis whitespace-nowrap">File saat ini:<br><span class="font-bold text-slate-700"><?php echo $data['gambar']; ?></span></div>
                </div>
                <?php endif; ?>

                <div class="relative">
                    <input type="file" name="gambar_baru" accept="image/*" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all font-medium file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-600 hover:file:bg-amber-100 file:cursor-pointer">
                </div>
                <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Biarkan kosong jika tidak ingin mengganti foto
                </p>
            </div>

            <div class="pt-5 flex flex-col sm:flex-row gap-3">
                <a href="index_admin.php" class="w-full sm:w-1/3 py-3.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors text-center border border-slate-200 shadow-sm">
                    Batal
                </a>
                <button type="submit" name="submit_edit" class="w-full sm:w-2/3 py-3.5 bg-amber-500 text-white font-extrabold rounded-xl hover:bg-amber-600 hover:-translate-y-0.5 transition-all shadow-lg shadow-amber-500/30 flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</body>
</html>