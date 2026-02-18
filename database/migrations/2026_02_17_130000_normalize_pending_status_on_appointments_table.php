<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('appointments')
            ->where('status', 'pendiente')
            ->update(['status' => 'pendiente_anticipo']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: evitamos revertir masivamente estados ya normalizados.
    }
};
