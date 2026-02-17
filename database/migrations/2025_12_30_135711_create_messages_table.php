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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Mesajı gönderen ve alan kişiler
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            
            // Mesaj içeriği (Dosya gönderilirse metin boş olabilir, o yüzden ->nullable() ekledik)
            $table->text('message')->nullable();

            // Dosya İşlemleri İçin Yeni Sütunlar
            $table->string('file_url')->nullable();  // Dosyanın yolu
            $table->string('file_type')->nullable(); // Dosya tipi: 'image' veya 'file'
            $table->string('file_name')->nullable(); // Dosyanın orijinal adı (örn: odev.pdf)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
