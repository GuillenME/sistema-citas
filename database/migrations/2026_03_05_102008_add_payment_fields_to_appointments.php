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
        Schema::table('appointments', function (Blueprint $table) {

            $table->decimal('service_price', 8, 2)->nullable()->after('service_id');
            $table->decimal('deposit_amount', 8, 2)->nullable()->after('service_price');
            $table->decimal('final_payment', 8, 2)->nullable()->after('deposit_amount');
            $table->decimal('total_paid', 8, 2)->nullable()->after('final_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {

            $table->dropColumn([
                'service_price',
                'deposit_amount',
                'final_payment',
                'total_paid'
            ]);
        });
    }
};
