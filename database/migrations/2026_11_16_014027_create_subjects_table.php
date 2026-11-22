<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel mata pelajaran / kelas milik guru
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // guru pembuat
            $table->string('name');                       // nama mapel, misal: Matematika
            $table->string('class_name');                 // misal: Class XI MIPA 1
            $table->text('description')->nullable();
            $table->string('banner_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};