<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->enum('video_type', ['youtube', 'vimeo', 'html5', 'external'])->default('html5')->after('video_url')->comment('Video türü');
            $table->string('video_id')->nullable()->after('video_type')->comment('Video platform ID (YouTube/Vimeo)');
            $table->string('video_format')->nullable()->after('video_id')->comment('Video formatı (mp4, webm vb.)');
            $table->text('embed_code')->nullable()->after('video_format')->comment('Harici video embed kodu');
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['video_type', 'video_id', 'video_format', 'embed_code']);
        });
    }
};
