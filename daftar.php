<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Wartan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-wartan { background-color: #1a8f50; }
        .text-wartan { color: #1a8f50; }
        .btn-wartan:hover { background-color: #147340; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white rounded-lg shadow-xl w-96 overflow-hidden">
        
        <div class="bg-wartan text-white text-center py-6">
            <h1 class="text-2xl font-bold">Daftar Akun Wartan</h1>
            <p class="text-sm opacity-90 mt-1">Buat akun untuk mulai memesan</p>
        </div>

        <div class="p-6">
            <form action="actions/proses_daftar.php" method="POST">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="nama_lengkap">
                        Nama Lengkap
                    </label>
                    <input class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" 
                           type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap Anda" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="username">
                        Username
                    </label>
                    <input class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" 
                           type="text" id="username" name="username" placeholder="Buat username" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                        Password
                    </label>
                    <input class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" 
                           type="password" id="password" name="password" placeholder="Buat password" required>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button class="bg-wartan text-white font-bold py-2 px-4 rounded-lg w-full focus:outline-none focus:shadow-outline btn-wartan transition duration-300" 
                            type="submit" name="submit_daftar">
                        DAFTAR SEKARANG
                    </button>
                </div>
                
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600 mb-2">Sudah punya akun? <a href="login.php" class="text-wartan font-semibold hover:underline">Masuk di sini</a></p>
                    <a href="index.php" class="inline-block text-sm text-gray-500 hover:text-green-600 border border-gray-300 rounded-lg py-2 px-4 w-full text-center transition duration-300">
                        Kembali ke Beranda
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
