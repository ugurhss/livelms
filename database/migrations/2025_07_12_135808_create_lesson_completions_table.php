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
      Schema::create('lesson_completions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Öğrenci ID');
    $table->foreignId('lesson_id')->constrained()->onDelete('cascade')->comment('Ders ID');
    $table->timestamps();

    $table->unique(['user_id', 'lesson_id'])->comment('Aynı dersin tekrar tamamlanmasını engelle');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_completions');
    }
};
