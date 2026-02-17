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
    Schema::create('quizzes', function (Blueprint $table) {
        $table->id();
        // Hangi kursa ait olduğu (Kurs silinirse sınav da silinsin -> cascade)
        $table->foreignId('course_id')->constrained()->onDelete('cascade');
        
        $table->string('title'); // Sınav Başlığı
        $table->text('description')->nullable(); // Açıklama
        $table->integer('time_limit')->nullable(); // Süre (dakika cinsinden)
        $table->integer('passing_score')->default(50); // Geçme notu
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
