<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - EduFlex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F5F6FA] min-h-screen">

    {{-- Top Navbar --}}
    <nav class="flex items-center justify-between px-6 md:px-10 py-5">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-600 to-orange-500 text-white font-extrabold flex items-center justify-center">E</div>
            <div class="leading-tight">
                <p class="font-extrabold text-lg"><span class="text-purple-700">Edu</span><span class="text-orange-500">Flex</span></p>
                <p class="text-[10px] text-purple-700">education flexible</p>
            </div>
        </div>
        <div class="flex items-center gap-6 md:gap-10 font-semibold text-sm">
            <a href="#" class="hidden sm:block">About</a>
            <a href="#" class="hidden sm:block">Tutorial</a>
            <a href="#" class="hidden sm:block">Service</a>
            @yield('nav_button')
        </div>
    </nav>

    {{-- Card utama dengan efek blur oranye (kiri atas) & ungu (kanan bawah) --}}
    <main class="px-4 md:px-12 pb-12">
        <div class="relative overflow-hidden rounded-3xl bg-[#F3F1F5] shadow-xl max-w-6xl mx-auto py-14 px-6">
            <div class="absolute -top-20 -left-20 w-96 h-96 rounded-full bg-orange-300/50 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-16 w-[28rem] h-[28rem] rounded-full bg-purple-400/40 blur-3xl"></div>
            <div class="absolute top-10 right-32 w-40 h-40 rounded-full bg-purple-300/40 blur-3xl"></div>

            <div class="relative max-w-md mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

    <script>
        // Show / hide password
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
