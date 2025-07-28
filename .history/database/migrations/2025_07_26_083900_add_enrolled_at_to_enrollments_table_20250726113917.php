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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->timestamp('enrolled_at')->nullable()->after('progress')->comment('Kayıt zamanı');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('enrolled_at');
        });
    }
};
