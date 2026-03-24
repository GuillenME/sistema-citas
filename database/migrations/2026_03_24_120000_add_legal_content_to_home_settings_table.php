<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->longText('terms_content')->nullable()->after('footer_hours');
            $table->longText('privacy_policy_content')->nullable()->after('terms_content');
            $table->timestamp('terms_updated_at')->nullable()->after('privacy_policy_content');
            $table->timestamp('privacy_policy_updated_at')->nullable()->after('terms_updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->dropColumn([
                'terms_content',
                'privacy_policy_content',
                'terms_updated_at',
                'privacy_policy_updated_at',
            ]);
        });
    }
};
