<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citizen_tips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('location_name')->nullable();
            $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->string('witness_name')->nullable();
            $table->string('witness_phone')->nullable();
            $table->string('witness_email')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('media_path')->nullable();
            $table->string('media_type', 20)->nullable(); // 'image', 'video', 'document'
            $table->string('status', 30)->default('pending'); // 'pending', 'verified', 'converted_to_article', 'rejected'
            $table->text('admin_notes')->nullable();
            $table->foreignId('converted_article_id')->nullable()->constrained('articles')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citizen_tips');
    }
};
