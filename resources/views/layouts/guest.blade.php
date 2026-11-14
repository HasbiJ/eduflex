<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>@yield('title') | EduFlex</title>
</head>
<body class="min-h-screen bg-[#F5F6FA] font-sans text-gray-900 antialiased">

    {{-- Top Navbar --}}
    <header class="max-w-[1600px] mx-auto flex items-center justify-between px-6 lg:px-10 py-5">
        <a href="{{ route('login') }}">@include('partials.logo')</a>
        <nav class="flex items-center gap-6 lg:gap-10 text-sm font-semibold">
            <a href="#" class="hidden sm:inline hover:text-orange-500">About</a>
            <a href="#" class="hidden sm:inline hover:text-orange-500">Tutorial</a>
            <a href="#" class="hidden sm:inline hover:text-orange-500">Service</a>
            <a href="{{ route('register') }}" class="rounded-full bg-orange-500 px-7 py-3 text-white shadow-xl shadow-orange-300/40 hover:bg-orange-600 transition">Register</a>
        </nav>
    </header>

    {{-- Card utama dengan radial blur blobs --}}
    <main class="px-4 sm:px-8 lg:px-20 pb-12">
        <section class="relative overflow-hidden rounded-3xl bg-[#F3F1F6] shadow-2xl shadow-gray-300/60 min-h-[640px] flex items-center justify-center py-12">
            <div class="pointer-events-none absolute -top-24 -left-24 h-[420px] w-[420px] rounded-full bg-orange-300/50 blur-3xl"></div>
            <div class="pointer-events-none absolute top-10 right-[12%] h-44 w-44 rounded-full bg-purple-300/40 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 -right-20 h-[460px] w-[460px] rounded-full bg-purple-400/40 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-10 left-[28%] h-52 w-52 rounded-full bg-orange-200/40 blur-3xl"></div>

            <div class="relative z-10 w-full max-w-md px-6">
                @yield('content')
            </div>
        </section>
    </main>

    <script>
        function togglePw(id, btn) {
            const input = document.getElementById(id);
            const hidden = input.type === 'password';
            input.type = hidden ? 'text' : 'password';
            btn.querySelector('.slash').classList.toggle('hidden', !hidden);
        }
    </script>
</body>
</html>
