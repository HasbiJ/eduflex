<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - EduFlex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F5F6FA] min-h-screen flex flex-col">

    {{-- Top Horizontal Navbar --}}
    <nav class="bg-white px-6 md:px-10 py-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-600 to-orange-500 text-white font-extrabold flex items-center justify-center">E</div>
            <div class="leading-tight">
                <p class="font-extrabold text-lg"><span class="text-purple-700">Edu</span><span class="text-orange-500">Flex</span></p>
                <p class="text-[10px] text-purple-700">education flexible</p>
            </div>
        </div>

        {{-- Menu tengah: menu aktif diberi pill oranye --}}
        @php
            $menu = [
                ['Home', '/dashboard', 'dashboard*'],
                ['Subjects', '/subjects', 'subjects*'],
                ['Learning Video', '/learning-videos', 'learning-videos*'],
                ['Attendance', '/attendance', 'attendance*'],
                ['Learning Schedule', '/schedule', 'schedule*'],
            ];
        @endphp
        <div class="hidden md:flex items-center gap-2 text-sm font-semibold">
            @foreach ($menu as $m)
                <a href="{{ url($m[1]) }}"
                   class="px-5 py-2 rounded-full {{ request()->is($m[2]) ? 'bg-orange-500 text-white shadow-lg shadow-orange-200' : 'hover:text-orange-500' }}">
                    {{ $m[0] }}
                </a>
            @endforeach
        </div>

        {{-- Profil guru + logout --}}
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-orange-400 text-white font-bold flex items-center justify-center">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="leading-tight">
                <p class="font-bold text-sm">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">Guru</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="ml-2 text-xs bg-orange-100 text-orange-600 font-bold px-3 py-1.5 rounded-full hover:bg-orange-200">Logout</button>
            </form>
        </div>
    </nav>

    {{-- Isi halaman --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-orange-100 mt-16">
        <div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-sm">
            <div class="col-span-2 md:col-span-1">
                <p class="font-extrabold text-lg">EduFlex</p>
                <p class="text-gray-500 mt-2">Providing flexible, high-quality education for the modern student. Empowering futures through technology.</p>
                <div class="flex gap-2 mt-4">
                    <span class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">🌐</span>
                    <span class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">🔗</span>
                    <span class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">✉</span>
                </div>
            </div>
            <div>
                <p class="font-bold text-orange-700 tracking-wider mb-3">COMPANY</p>
                <ul class="space-y-2 text-gray-600"><li>About Us</li><li>Careers</li><li>Contact Support</li></ul>
            </div>
            <div>
                <p class="font-bold text-orange-700 tracking-wider mb-3">RESOURCES</p>
                <ul class="space-y-2 text-gray-600"><li>API Docs</li><li>Help Center</li><li>Newsletter</li></ul>
            </div>
            <div>
                <p class="font-bold text-orange-700 tracking-wider mb-3">LEGAL</p>
                <ul class="space-y-2 text-gray-600"><li>Privacy Policy</li><li>Terms of Service</li></ul>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-6 pb-8 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
            <p>© 2026 EduFlex Learning Systems. All rights reserved.</p>
            <span class="bg-orange-100 text-orange-700 font-bold px-3 py-1 rounded">v2.4.0-BETA</span>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
