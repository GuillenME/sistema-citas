<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // servicios
            $table->string('file');
            $table->string('status');
            $table->timestamp('imported_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
