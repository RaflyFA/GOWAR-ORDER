<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Wartan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Sedikit custom color sesuai brand Wartan */
        .bg-wartan { background-color: #1a8f50; }
        .text-wartan { color: #1a8f50; }
        .btn-wartan:hover { background-color: #147340; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white rounded-lg shadow-xl w-96 overflow-hidden">
        
        <div class="bg-wartan text-white text-center py-6">
            <div class="flex justify-center items-center gap-2 mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                <h1 class="text-2xl font-bold">Wartan</h1>
            </div>
            <p class="text-sm opacity-90">Silakan login untuk melanjutkan</p>
        </div>

        <div class="p-6">
            <form action="actions/proses_login.php" method="POST">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="username">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" 
                               type="text" id="username" name="username" placeholder="admin" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" 
                               type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button class="bg-wartan text-white font-bold py-2 px-4 rounded-lg w-full focus:outline-none focus:shadow-outline btn-wartan transition duration-300" 
                            type="submit" name="submit_login">
                        MASUK SEKARANG
                    </button>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="index.php" class="inline-block text-sm text-gray-500 hover:text-green-600 border border-gray-300 rounded-lg py-2 px-4 w-full text-center transition duration-300">
                        Kembali ke Menu Utama
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>