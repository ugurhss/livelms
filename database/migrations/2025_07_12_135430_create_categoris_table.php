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
       Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name')->comment('Kategori adı');
    $table->string('slug')->unique()->comment('SEO dostu URL');
    $table->text('description')->nullable()->comment('Kategori açıklaması');
    $table->string('icon')->nullable()->comment('İkon URL veya CSS sınıfı');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoris');
    }
};
