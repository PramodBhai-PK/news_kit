<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. States / Regions Table
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // हिंदी नाम (उदा. 'उत्तर प्रदेश')
            $table->string('name_en')->nullable(); // English Name ('Uttar Pradesh')
            $table->string('slug')->unique();
            $table->string('code', 10)->nullable(); // UP, BR, DL, MP, RJ, etc.
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Cities / Districts Table
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // हिंदी नाम (उदा. 'लखनऊ')
            $table->string('name_en')->nullable(); // English Name ('Lucknow')
            $table->string('slug')->unique();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Add Location fields to Articles Table
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('state_id')->nullable()->after('category_id')->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->after('state_id')->constrained('cities')->nullOnDelete();
            $table->string('location_name', 100)->nullable()->after('city_id'); // Optional specific locality/tehsil
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['state_id', 'city_id', 'location_name']);
        });

        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
    }
};
