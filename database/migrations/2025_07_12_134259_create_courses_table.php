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
     Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->string('title')->comment('Kurs başlığı');
    $table->text('description')->comment('Kurs açıklaması');
    $table->string('slug')->unique()->comment('SEO dostu URL')->nullable();
    $table->string('thumbnail')->comment('Kurs kapak resmi URL')->nullable();
    $table->string('category')->comment('Kurs kategorisi')->nullable();
    $table->enum('level', ['beginner', 'intermediate', 'advanced'])->comment('Zorluk seviyesi');
    $table->decimal('price', 8, 2)->comment('Satış fiyatı');
    $table->decimal('original_price', 8, 2)->comment('Orijinal fiyat (indirim öncesi)');
    $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->comment('Yayın durumu');
    $table->json('outcomes')->nullable()->comment('Kazanılacak beceriler (JSON array)');
    $table->json('prerequisites')->nullable()->comment('Ön koşul bilgileri (JSON array)');
    $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Eğitmen ID (users tablosu ile ilişki)');
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
