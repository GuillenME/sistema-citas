<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->string('footer_address')->nullable()->after('navbar_logo');
            $table->string('footer_phone')->nullable()->after('footer_address');
            $table->string('footer_hours')->nullable()->after('footer_phone');
        });
    }

    public function down(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->dropColumn(['footer_address', 'footer_phone', 'footer_hours']);
        });
    }
};
