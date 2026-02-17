<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/...._create_courses_table.php

public function up()
{
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->comment('Eğitmen ID')->constrained('users'); // Eğitmen (User)
        $table->foreignId('category_id')->nullable()->constrained('categories'); // Kategori
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('description');
        $table->string('thumbnail_path')->nullable(); // Kapak fotoğrafı
        $table->enum('status', ['draft', 'published', 'private'])->default('draft');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
