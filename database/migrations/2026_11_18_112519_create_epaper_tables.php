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
        Schema::create('epaper_editions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('edition_date')->index();
            $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->string('cover_image')->nullable();
            $table->string('pdf_file')->nullable();
            $table->integer('total_pages')->default(1);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('epaper_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epaper_edition_id')->constrained('epaper_editions')->cascadeOnDelete();
            $table->integer('page_number');
            $table->string('page_title')->nullable();
            $table->string('page_image');
            $table->string('pdf_url')->nullable();
            $table->timestamps();

            $table->unique(['epaper_edition_id', 'page_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epaper_pages');
        Schema::dropIfExists('epaper_editions');
    }
};
