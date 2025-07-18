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
      Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');  // courses tablosuna ilişki
            $table->foreignId('user_id')->constrained()->onDelete('cascade');    // yorumu yapan kullanıcı
            $table->tinyInteger('rating')->comment('1-5 arası puan');             // puan
            $table->text('comment')->nullable()->comment('Yorum metni');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
