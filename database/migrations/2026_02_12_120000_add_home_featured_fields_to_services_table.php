<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('featured_on_home')
                ->default(false)
                ->after('active');
            $table->unsignedTinyInteger('home_position')
                ->nullable()
                ->after('featured_on_home');
        });

        $featuredIds = DB::table('services')
            ->where('active', 1)
            ->orderByDesc('id')
            ->limit(10)
            ->pluck('id');

        foreach ($featuredIds as $index => $id) {
            DB::table('services')
                ->where('id', $id)
                ->update([
                    'featured_on_home' => 1,
                    'home_position' => $index + 1,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['featured_on_home', 'home_position']);
        });
    }
};
