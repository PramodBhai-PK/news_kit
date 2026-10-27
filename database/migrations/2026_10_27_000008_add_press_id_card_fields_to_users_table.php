<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('press_id_code')->nullable()->unique()->after('role');
            $table->string('designation')->nullable()->after('press_id_code');
            $table->string('blood_group', 10)->nullable()->after('designation');
            $table->string('bureau_location')->nullable()->after('blood_group');
            $table->string('emergency_contact')->nullable()->after('bureau_location');
            $table->date('id_card_issued_at')->nullable()->after('emergency_contact');
            $table->date('id_card_expires_at')->nullable()->after('id_card_issued_at');
            $table->string('id_card_status', 20)->default('active')->after('id_card_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'press_id_code',
                'designation',
                'blood_group',
                'bureau_location',
                'emergency_contact',
                'id_card_issued_at',
                'id_card_expires_at',
                'id_card_status',
            ]);
        });
    }
};
