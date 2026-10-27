<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_ad_views_count')->default(0)->after('custom_ad_code');
            $table->unsignedBigInteger('custom_ad_clicks_count')->default(0)->after('custom_ad_views_count');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['custom_ad_views_count', 'custom_ad_clicks_count']);
        });
    }
};
