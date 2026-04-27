<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - GOWAR</title>
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
                            600: '#1a8f50', // Original bg-wartan
                            700: '#15803d',
                            800: '#147340', // Original hover-wartan
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom Background & Glassmorphism */
        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=2000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        /* Smooth Input Transition */
        input:focus {
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <!-- Redesigned Login Card -->
    <div class="glass-panel rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        
        <!-- Header Section -->
        <div class="px-8 pt-10 pb-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-wartan-50 text-wartan-600 mb-4 shadow-sm border border-wartan-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Welcome Back</h1>
            <p class="text-gray-500 font-medium">Masuk ke sistem manajemen GOWAR</p>
        </div>

        <!-- Form Section -->
        <div class="px-8 pb-10">
            <!-- PENTING: Action harus tetap ke actions/proses_login.php, method POST -->
            <form action="actions/proses_login.php" method="POST" class="space-y-5">
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2 ml-1" for="username">
                        Username
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none transition-colors group-focus-within:text-wartan-600 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:outline-none focus:border-wartan-500 transition-all duration-300 font-medium placeholder-gray-400" 
                               type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="off">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2 ml-1" for="password">
                        Password
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none transition-colors group-focus-within:text-wartan-600 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:outline-none focus:border-wartan-500 transition-all duration-300 font-medium placeholder-gray-400" 
                               type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="pt-2">
                    <!-- PENTING: name="submit_login" jangan diubah karena dibutuhkan backend -->
                    <button class="w-full bg-wartan-600 hover:bg-wartan-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-green-500/30 transform transition-all duration-300 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-green-500/50 flex items-center justify-center gap-2" 
                            type="submit" name="submit_login">
                        <span>Masuk Dashboard</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="index.php" class="inline-flex items-center justify-center gap-2 text-sm font-semibold text-gray-500 hover:text-wartan-600 transition-colors w-full py-3 rounded-xl hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Halaman Utama
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>