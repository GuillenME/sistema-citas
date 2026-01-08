<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cita_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();
            $table->string('estado');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->timestamp('fecha_cambio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cita_estados');
    }
};

