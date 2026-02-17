<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
{
    // 1. Ödevler Tablosu (Eğitmenin oluşturduğu)
    Schema::create('assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained()->onDelete('cascade'); // Hangi kursun ödevi?
        $table->string('title');
        $table->text('description')->nullable();
        $table->dateTime('due_date'); // Son teslim tarihi
        $table->timestamps();
    });

    // 2. Ödev Teslimleri Tablosu (Öğrencinin yüklediği)
    Schema::create('assignment_submissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Hangi öğrenci?
        $table->string('file_path'); // Yüklenen dosyanın yolu
        $table->text('student_note')->nullable(); // Öğrenci notu
        $table->dateTime('submitted_at'); // Ne zaman yükledi?
        $table->integer('grade')->nullable(); // Puan (Opsiyonel)
        $table->text('feedback')->nullable(); // Eğitmen geri bildirimi
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
