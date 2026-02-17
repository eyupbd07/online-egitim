<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    // Lessons tablosuna içerik sütunu ekle
    Schema::table('lessons', function (Blueprint $table) {
        if (!Schema::hasColumn('lessons', 'content')) {
            $table->text('content')->nullable()->after('title');
        }
    });
    // Quizzes tablosuna süre ve baraj puanı sütunlarını ekle (Eğer yoksa)
    Schema::table('quizzes', function (Blueprint $table) {
        if (!Schema::hasColumn('quizzes', 'duration')) {
            $table->integer('duration')->default(30)->after('title');
        }
        if (!Schema::hasColumn('quizzes', 'passing_score')) {
            $table->integer('passing_score')->default(50)->after('duration');
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
