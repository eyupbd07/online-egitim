<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/...._create_assignments_table.php

public function up()
{
    Schema::create('assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
        $table->string('title');
        $table->text('description');
        $table->dateTime('due_date')->nullable(); // Teslim tarihi
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
