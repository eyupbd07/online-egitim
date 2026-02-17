<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // database/migrations/...._create_qna_messages_table.php

public function up()
{
    Schema::create('qna_messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users'); // Soruyu soran/cevaplayan
        $table->foreignId('parent_id')->nullable()->constrained('qna_messages')->onDelete('cascade'); // Cevapsa, sorunun ID'si
        $table->text('message');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qna_messages');
    }
};
