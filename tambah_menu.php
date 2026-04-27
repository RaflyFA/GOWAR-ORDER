<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu - GOWAR Admin</title>
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
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-wartan-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    </div>

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 w-full max-w-xl border border-white p-8 md:p-10 transition-all hover:shadow-2xl hover:shadow-slate-200/60 relative">
        
        <div class="absolute top-0 right-0 p-8">
            <a href="index_admin.php" class="text-slate-400 hover:text-red-500 hover:rotate-90 transition-all duration-300 block bg-slate-50 hover:bg-red-50 p-2 rounded-xl border border-slate-100 hover:border-red-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>

        <div class="mb-8 pr-12">
            <div class="w-14 h-14 bg-wartan-50 text-wartan-600 rounded-2xl flex items-center justify-center mb-4 border border-wartan-100">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Tambah Menu Baru</h2>
            <p class="text-slate-500 mt-1 font-medium">Lengkapi detail menu di bawah ini untuk ditambahkan ke daftar warung.</p>
        </div>

        <form action="actions/proses_tambah_menu.php" method="POST" enctype="multipart/form-data" class="space-y-5">
            
            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-slate-700">Nama Menu</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <input type="text" name="nama_menu" placeholder="Contoh: Nasi Goreng Spesial" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-wartan-500/20 focus:border-wartan-500 transition-all shadow-sm font-medium" required>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-slate-700">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsikan komposisi atau rasa menu..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-wartan-500/20 focus:border-wartan-500 transition-all shadow-sm font-medium resize-none"></textarea>
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-slate-700">Harga (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold">
                        Rp
                    </div>
                    <input type="number" name="harga" placeholder="0" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-wartan-500/20 focus:border-wartan-500 transition-all shadow-sm font-bold text-lg" required>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-slate-700">Foto Makanan</label>
                <div class="relative">
                    <input type="file" name="gambar" accept="image/*" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-wartan-500/20 focus:border-wartan-500 transition-all shadow-sm font-medium file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-wartan-50 file:text-wartan-600 hover:file:bg-wartan-100 file:cursor-pointer" required>
                </div>
                <p class="text-xs text-slate-400 mt-1">* Format yang didukung: JPG, PNG, JPEG. Maks 2MB.</p>
            </div>

            <div class="pt-4 flex flex-col sm:flex-row gap-3">
                <a href="index_admin.php" class="w-full sm:w-1/3 py-3.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors text-center border border-slate-200 shadow-sm">
                    Batal
                </a>
                <button type="submit" name="submit_tambah" class="w-full sm:w-2/3 py-3.5 bg-wartan-600 text-white font-extrabold rounded-xl hover:bg-wartan-700 hover:-translate-y-0.5 transition-all shadow-lg shadow-wartan-500/30 flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Menu ke Database
                </button>
            </div>
        </form>
    </div>

</body>
</html>