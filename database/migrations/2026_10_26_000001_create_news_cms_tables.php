<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('hindi_name')->nullable();
            $table->text('description')->nullable();
            $table->string('color', 20)->default('#E11D48'); // Color badge for category
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tags
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 3. Articles (News Posts)
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('image_caption')->nullable();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['published', 'draft', 'archived'])->default('published');
            $table->boolean('is_breaking')->default(false); // For Breaking News ticker
            $table->boolean('is_featured')->default(false); // For Hero slider/highlight
            $table->boolean('is_trending')->default(false); // For Trending top 5
            $table->unsignedBigInteger('views_count')->default(0);
            $table->integer('reading_time')->default(3); // In minutes
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 4. Article Tags Pivot
        Schema::create('article_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->unique(['article_id', 'tag_id']);
        });

        // 5. Comments
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->string('user_name');
            $table->string('user_email');
            $table->text('comment');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        // 6. Settings (Centralized Branding, Logos, Names, Social Links)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, color, image, boolean
            $table->string('group')->default('general'); // branding, social, general, seo
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
    }
};
