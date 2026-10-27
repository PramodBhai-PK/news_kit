<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Dedicated Advertisements table for multiple global & sidebar ads
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Optional Title
            $table->enum('type', ['image', 'code'])->default('image'); // 'image' or 'code' (Google AdSense/HTML)
            $table->string('image_url')->nullable();
            $table->string('destination_url')->nullable();
            $table->longText('html_code')->nullable(); // Google AdSense script or custom HTML
            $table->string('placement')->default('sidebar'); // 'sidebar', 'header', 'content_bottom'
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->timestamps();
        });

        // 2. Add custom ad fields to articles table for article-specific ads
        Schema::table('articles', function (Blueprint $table) {
            $table->string('custom_ad_title')->nullable()->after('published_at');
            $table->string('custom_ad_type')->default('none')->after('custom_ad_title'); // 'none', 'image', 'code'
            $table->string('custom_ad_image')->nullable()->after('custom_ad_type');
            $table->string('custom_ad_link')->nullable()->after('custom_ad_image');
            $table->longText('custom_ad_code')->nullable()->after('custom_ad_link');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['custom_ad_title', 'custom_ad_type', 'custom_ad_image', 'custom_ad_link', 'custom_ad_code']);
        });

        Schema::dropIfExists('advertisements');
    }
};
