@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-6xl mx-auto px-6 pt-8">

    {{-- Alert Notifikasi CRUD --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="font-bold text-lg leading-none">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1 class="text-3xl font-extrabold text-slate-900 mb-6">Profile</h1>

    {{-- Banner Cover + Avatar Overlap --}}
    <div class="relative mb-6">
        <div class="w-full h-56 md:h-64 rounded-2xl overflow-hidden bg-slate-900 shadow-sm">
            <img src="https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?auto=format&fit=crop&w=1400&q=80"
                 alt="Banner" class="w-full h-full object-cover opacity-85">
        </div>

        {{-- Foto Profil Bulat --}}
        <div class="absolute -bottom-14 left-8">
            <div class="w-32 h-32 md:w-36 md:h-36 rounded-full border-4 border-white bg-slate-200 overflow-hidden shadow-md">
                <img src="{{ $teacher->avatar ? asset('storage/' . $teacher->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=256&background=1E293B&color=fff' }}"
                     alt="{{ $user->name }}" class="w-full h-full object-cover">
            </div>
        </div>
    </div>

    {{-- Tab Menu (Profile, Security, Notifications) --}}
    <div class="flex justify-center md:justify-start md:pl-52 mb-6">
        <div class="flex items-center gap-8 text-sm font-semibold">
            <button onclick="switchTab('profile')" id="tab-btn-profile" class="pb-2 border-b-2 border-orange-500 text-orange-500 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                Profile
            </button>
            <button onclick="switchTab('security')" id="tab-btn-security" class="pb-2 border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Security
            </button>
            <button onclick="switchTab('notifications')" id="tab-btn-notifications" class="pb-2 border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Notifications
            </button>
        </div>
    </div>

    {{-- TAB 1: PROFILE --}}
    <div id="tab-content-profile" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Kolom Kiri (2/3): Nama & Biodata --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Card Nama + Tombol Edit Pensil --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm px-7 py-5 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-900">
                    {{ $teacher->title_prefix ? $teacher->title_prefix . ' ' : '' }}{{ $user->name }}
                </h2>
                <button onclick="openEditModal()" title="Edit Profil" class="p-2 text-slate-600 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </button>
            </div>

            {{-- Card Detail Informasi --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-7">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-xs text-slate-400 font-medium mb-1">NIP</p>
                        <p class="text-base font-bold text-slate-800">{{ $teacher->nip }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-medium mb-1">Email</p>
                        <p class="text-base font-bold text-slate-800 underline decoration-slate-400">{{ $user->email }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-medium mb-1">Teacher</p>
                        <p class="text-base font-bold text-slate-800">{{ $teacher->subject }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-medium mb-1">Number Phone</p>
                        <p class="text-base font-bold text-slate-800">{{ $teacher->phone ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-medium mb-1">Address</p>
                        <p class="text-base font-bold text-slate-800 leading-snug max-w-[230px]">{{ $teacher->address ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-medium mb-1">Date of Birth</p>
                        <p class="text-base font-bold text-slate-800">
                            {{ $teacher->date_of_birth ? $teacher->date_of_birth->format('d - m - Y') : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-medium mb-1">Religion</p>
                        <p class="text-base font-bold text-slate-800">{{ $teacher->religion ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan (1/3): Kartu Tanda Pegawai (QR Code) --}}
        <div id="id-card-box" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-7 flex flex-col items-center text-center mt-0 lg:-mt-20">
            <h3 class="text-base font-bold text-slate-800 mb-5">Kartu tanda pegawai</h3>

            <div class="p-3 bg-white border border-slate-100 rounded-xl shadow-inner mb-5">
                <div id="qrcode"></div>
            </div>

            <p class="text-xs text-slate-400 leading-relaxed max-w-[230px] mb-6">
                Pindai kode QR untuk identifikasi resmi dalam lingkungan sekolah dan perpustakaan digital.
            </p>

            <button onclick="downloadIdCard()" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm py-2.5 px-4 rounded-lg shadow-md shadow-orange-200 flex items-center justify-center gap-2 transition mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh ID Card
            </button>

            <button onclick="shareProfile()" class="w-full bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm py-2.5 px-4 rounded-lg border border-slate-200 flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                Bagikan
            </button>
        </div>
    </div>

    {{-- TAB 2: SECURITY (Ubah Password) --}}
    <div id="tab-content-security" class="hidden bg-white rounded-2xl border border-slate-200 p-7 max-w-xl">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Ubah Password</h3>
        <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Password Lama</label>
                <input type="password" name="current_password" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Password Baru (min. 6 karakter)</label>
                <input type="password" name="password" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">Simpan Password</button>
        </form>
    </div>

    {{-- TAB 3: NOTIFICATIONS --}}
    <div id="tab-content-notifications" class="hidden bg-white rounded-2xl border border-slate-200 p-7 max-w-xl">
        <h3 class="text-lg font-bold text-slate-900 mb-2">Pengaturan Notifikasi</h3>
        <p class="text-sm text-slate-500">Notifikasi jadwal mengajar dan presensi aktif dikirimkan ke <strong>{{ $user->email }}</strong>.</p>
    </div>

</div>

{{-- MODAL EDIT PROFIL (UPDATE & DELETE) --}}
<div id="editModal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit Profil Guru</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
        </div>

        <form action="{{ route('profile.update', $teacher->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Foto Profil</label>
                <input type="file" name="avatar" accept="image/*" class="w-full text-xs border border-slate-200 rounded-lg p-2">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Sapaan</label>
                    <select name="title_prefix" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        <option value="Bu" {{ $teacher->title_prefix == 'Bu' ? 'selected' : '' }}>Bu</option>
                        <option value="Pak" {{ $teacher->title_prefix == 'Pak' ? 'selected' : '' }}>Pak</option>
                        <option value="" {{ empty($teacher->title_prefix) ? 'selected' : '' }}>Tanpa Sapaan</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip', $teacher->nip) }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Mata Pelajaran</label>
                    <input type="text" name="subject" value="{{ old('subject', $teacher->subject) }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : '') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Agama</label>
                    <select name="religion" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $rel)
                            <option value="{{ $rel }}" {{ $teacher->religion == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Alamat</label>
                <textarea name="address" rows="2" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('address', $teacher->address) }}</textarea>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg">Simpan Perubahan</button>
            </div>
        </form>

        {{-- Tombol Delete Data Biodata (untuk demo CRUD) --}}
        <form action="{{ route('profile.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Reset/hapus data detail profil guru ini?')" class="mt-4 pt-3 border-t flex justify-between items-center">
            @csrf
            @method('DELETE')
            <span class="text-xs text-slate-400">Hapus/reset biodata pegawai?</span>
            <button type="submit" class="text-xs text-red-600 hover:underline font-semibold">Hapus Data Profil</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // Generate QR Code otomatis dari NIP & Nama Guru
    new QRCode(document.getElementById("qrcode"), {
        text: "EDUFLEX-NIP:{{ $teacher->nip }}|{{ $user->name }}",
        width: 150,
        height: 150,
        colorDark : "#0f172a",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });

    function openEditModal() {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    function switchTab(tab) {
        ['profile', 'security', 'notifications'].forEach(t => {
            document.getElementById('tab-content-' + t).classList.add('hidden');
            const btn = document.getElementById('tab-btn-' + t);
            btn.classList.remove('border-orange-500', 'text-orange-500');
            btn.classList.add('border-transparent', 'text-slate-500');
        });
        document.getElementById('tab-content-' + tab).classList.remove('hidden');
        const activeBtn = document.getElementById('tab-btn-' + tab);
        activeBtn.classList.add('border-orange-500', 'text-orange-500');
        activeBtn.classList.remove('border-transparent', 'text-slate-500');
    }

    function downloadIdCard() {
        const card = document.getElementById('id-card-box');
        html2canvas(card).then(canvas => {
            const link = document.createElement('a');
            link.download = 'ID-Card-{{ $teacher->nip }}.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    }

    function shareProfile() {
        navigator.clipboard.writeText(window.location.href);
        alert('Tautan profil berhasil disalin!');
    }
</script>
@endsection