<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel stream: pengumuman (news) + classwork (task, quiz, module)
        Schema::create('subject_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type');                       // news / task / quiz / module
            $table->string('title');
            $table->text('content')->nullable();
            $table->date('deadline')->nullable();
            $table->integer('max_score')->default(100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_posts');
    }
};
