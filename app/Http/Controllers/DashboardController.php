<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy jadwal mengajar hari ini
        $jadwal = [
            ['mapel' => 'Matematika', 'kelas' => 'Class XI MIPA 1', 'jam' => '07:00 - 09:00', 'kode' => 'KAB1'],
            ['mapel' => 'Matematika', 'kelas' => 'Class XI MIPA 2', 'jam' => '10:00 - 12:00', 'kode' => 'KAB1'],
            ['mapel' => 'Matematika', 'kelas' => 'Class XI MIPA 3', 'jam' => '13:00 - 15:00', 'kode' => 'KAB1'],
        ];

        // Data dummy 4 kartu statistik
        $statistik = [
            ['judul' => 'Active Quizzes',   'angka' => 1,   'badge' => '2 Expiring'],
            ['judul' => 'Uploaded Videos',  'angka' => 1,   'badge' => '4.2 TB used'],
            ['judul' => 'Total Students',   'angka' => 120, 'badge' => '98% Retention'],
            ['judul' => 'Classes Managed',  'angka' => 3,   'badge' => '+2 this month'],
        ];

        return view('dashboard.index', compact('jadwal', 'statistik'));
    }
}
