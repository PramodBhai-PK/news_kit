<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('video_type')->default('none')->after('image_caption'); // 'none', 'youtube', 'direct'
            $table->text('video_url')->nullable()->after('video_type'); // YouTube URL or direct MP4 URL/storage path
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['video_type', 'video_url']);
        });
    }
};
