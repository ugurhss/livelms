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
       Schema::create('lessons', function (Blueprint $table) {
    $table->id();
    $table->string('title')->comment('Ders başlığı');
    $table->string('slug')->unique()->comment('SEO dostu URL');
    $table->text('description')->comment('Ders açıklaması');
    $table->integer('duration_minutes')->comment('Ders süresi (dakika)');
    $table->string('video_url')->comment('Video içerik URL');
    $table->integer('order')->default(0)->comment('Sıralama düzeni');
    $table->boolean('is_free')->default(false)->comment('Ücretsiz ders mi?');
    $table->foreignId('course_id')->constrained()->onDelete('cascade')->comment('Kurs ID (courses tablosu ile ilişki)');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
