@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Hero Banner --}}
    <section class="relative h-72 md:h-96 bg-cover bg-center"
             style="background-image: url('https://images.unsplash.com/photo-1562774053-701939374585?w=1600');">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative h-full max-w-6xl mx-auto px-6 flex items-center">
            <h1 class="text-white text-5xl md:text-7xl font-extrabold leading-tight">
                Welcome,<br>{{ Auth::user()->name }}
            </h1>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-6">

        {{-- Section 1: Jadwal mengajar --}}
        <h2 class="text-xl font-semibold mt-8 mb-3">Today's Teaching Schedule</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach ($jadwal as $j)
                <div class="bg-white rounded-2xl border border-orange-100 p-6 h-44 flex flex-col justify-between">
                    <div class="flex justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $j['mapel'] }}</h3>
                            <p class="text-xs mt-1">{{ $j['kelas'] }}</p>
                        </div>
                        <span class="text-gray-500 font-bold">•••</span>
                    </div>
                    <div class="flex justify-between text-xs font-semibold">
                        <span>{{ $j['jam'] }}</span>
                        <span>{{ $j['kode'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Section 2: Grafik --}}
        <h2 class="text-xl font-semibold mt-10 mb-3">Subject Performance Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-2xl border border-orange-100 p-6">
                <p class="font-bold text-sm mb-4">Subject Performance Overview</p>
                <canvas id="lineChart" height="200"></canvas>
                <a href="#" class="inline-block mt-5 bg-orange-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-lg shadow-orange-200">Recapitulation</a>
            </div>
            <div class="bg-white rounded-2xl border border-orange-100 p-6">
                <p class="font-bold text-sm mb-4">Subject Performance Overview</p>
                <canvas id="barChart" height="200"></canvas>
            </div>
        </div>

        {{-- Section 3: Kartu statistik --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mt-8">
            @foreach ($statistik as $s)
                <div class="bg-white rounded-2xl border border-orange-100 p-5">
                    <div class="flex justify-between items-start">
                        <div class="w-9 h-9 rounded-lg bg-orange-500"></div>
                        <span class="text-[11px] font-semibold text-teal-700">{{ $s['badge'] }}</span>
                    </div>
                    <p class="text-xs font-bold mt-3">{{ $s['judul'] }}</p>
                    <p class="text-3xl font-extrabold">{{ $s['angka'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ===== Line / Area Chart (oranye, melengkung) =====
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                data: [80, 62, 85, 92, 88, 93, 0],
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245, 158, 11, 0.12)',
                fill: true,
                tension: 0.4,          // membuat garis melengkung
                pointBackgroundColor: '#F97316',
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { min: 0, max: 100 } }
        }
    });

    // ===== Bar Chart (gradien ungu-putih, sudut atas membulat) =====
    const ctxBar = document.getElementById('barChart').getContext('2d');
    const gradient = ctxBar.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, '#A855F7');
    gradient.addColorStop(1, '#FFFFFF');

    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Class A1', 'Class A2', 'Class B1', 'Class C1'],
            datasets: [{
                data: [85, 65, 95, 50],
                backgroundColor: gradient,
                borderRadius: { topLeft: 20, topRight: 20 },
                barPercentage: 0.35,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { min: 0, max: 100 } }
        }
    });
</script>
@endsection
