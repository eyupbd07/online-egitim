<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // database/migrations/...._create_questions_table.php
public function up()
{
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quiz_id')->constrained()->onDelete('cascade'); // Hangi sınava ait olduğu
        $table->text('question_text'); // Soru metni
        
        $table->string('option_a');
        $table->string('option_b');
        $table->string('option_c');
        $table->string('option_d');
        
        // --- EKSİK OLAN SATIR BU (BUNU EKLE) ---
        $table->string('correct_answer')->default('option_a'); // Doğru cevap
        // ---------------------------

        $table->integer('points')->default(10); // Puan
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
