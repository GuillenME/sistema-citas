<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('importaciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // servicios
            $table->string('archivo');
            $table->string('estado');
            $table->timestamp('importado_en')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('importaciones');
    }
};
