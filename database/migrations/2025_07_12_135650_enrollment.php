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
        Schema::create('enrollments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Öğrenci ID (users tablosu ile ilişki)');
    $table->foreignId('course_id')->constrained()->onDelete('cascade')->comment('Kurs ID (courses tablosu ile ilişki)');
    $table->float('progress')->default(0)->comment('Tamamlanma yüzdesi');
    $table->timestamp('completed_at')->nullable()->comment('Tamamlanma tarihi');
    $table->timestamps();

    $table->unique(['user_id', 'course_id'])->comment('Aynı kursa tekrar kaydı engelle');
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
