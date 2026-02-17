<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Öğrenci
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade'); // Sınav
            $table->integer('score'); // Puan (0-100)
            $table->integer('correct_answers'); // Doğru sayısı
            $table->integer('wrong_answers'); // Yanlış sayısı
            $table->json('answers')->nullable(); // Tüm cevaplar
            $table->boolean('is_passed'); // Geçti/Kaldı
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};
