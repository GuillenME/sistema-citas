<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        // Renombrar tablas
        $this->renameTable('usuarios', 'users');
        $this->renameTable('clientes', 'clients');
        $this->renameTable('personal', 'staff');
        $this->renameTable('empleados', 'employees');
        $this->renameTable('servicios', 'services');
        $this->renameTable('citas', 'appointments');
        $this->renameTable('cita_estados', 'appointment_states');
        $this->renameTable('noticias', 'news');
        $this->renameTable('promociones', 'promotions');
        $this->renameTable('paginas', 'pages');
        $this->renameTable('importaciones', 'imports');
        $this->renameTable('tareas_programadas', 'scheduled_tasks');

        // Renombrar columnas usando SQL directo
        if ($driver === 'mysql') {
            $this->renameColumnsMySQL();
        } else {
            // Para otras bases de datos, usar el método estándar
            $this->renameColumnsStandard();
        }
    }

    /**
     * Renombrar tablas
     */
    private function renameTable($oldName, $newName): void
    {
        if (Schema::hasTable($oldName) && !Schema::hasTable($newName)) {
            Schema::rename($oldName, $newName);
        }
    }

    /**
     * Renombrar columnas para MySQL usando SQL directo
     */
    private function renameColumnsMySQL(): void
    {
        $dbName = DB::getDatabaseName();
        
        // users
        if (Schema::hasTable('users')) {
            $this->renameColumnMySQL('users', 'nombre', 'name', 'VARCHAR(255)');
            $this->renameColumnMySQL('users', 'apellido', 'last_name', 'VARCHAR(255)');
            $this->renameColumnMySQL('users', 'telefono', 'phone', 'VARCHAR(255)');
            $this->renameColumnMySQL('users', 'rol_id', 'role_id', 'BIGINT UNSIGNED');
            $this->renameColumnMySQL('users', 'activo', 'active', 'TINYINT(1)');
            $this->renameColumnMySQL('users', 'aviso_enviado', 'notice_sent', 'TINYINT(1)');
        }

        // roles
        if (Schema::hasTable('roles')) {
            $this->renameColumnMySQL('roles', 'nombre', 'name', 'VARCHAR(255)');
        }

        // clients
        if (Schema::hasTable('clients')) {
            $this->renameColumnMySQL('clients', 'usuario_id', 'user_id', 'BIGINT UNSIGNED');
            $this->renameColumnMySQL('clients', 'fecha_nacimiento', 'birth_date', 'DATE');
            $this->renameColumnMySQL('clients', 'notas', 'notes', 'TEXT');
        }

        // staff
        if (Schema::hasTable('staff')) {
            $this->renameColumnMySQL('staff', 'usuario_id', 'user_id', 'BIGINT UNSIGNED');
            $this->renameColumnMySQL('staff', 'especialidad', 'specialty', 'VARCHAR(255)');
            $this->renameColumnMySQL('staff', 'activo', 'active', 'TINYINT(1)');
        }

        // employees
        if (Schema::hasTable('employees')) {
            $this->renameColumnMySQL('employees', 'nombre', 'name', 'VARCHAR(255)');
            $this->renameColumnMySQL('employees', 'telefono', 'phone', 'VARCHAR(255)');
            $this->renameColumnMySQL('employees', 'especialidad', 'specialty', 'VARCHAR(255)');
            $this->renameColumnMySQL('employees', 'activo', 'active', 'TINYINT(1)');
        }

        // services
        if (Schema::hasTable('services')) {
            $this->renameColumnMySQL('services', 'nombre', 'name', 'VARCHAR(255)');
            $this->renameColumnMySQL('services', 'descripcion', 'description', 'TEXT');
            $this->renameColumnMySQL('services', 'duracion_minutos', 'duration_minutes', 'INT');
            $this->renameColumnMySQL('services', 'precio', 'price', 'DECIMAL(8,2)');
            $this->renameColumnMySQL('services', 'activo', 'active', 'TINYINT(1)');
        }

        // appointments
        if (Schema::hasTable('appointments')) {
            $this->renameColumnMySQL('appointments', 'cliente_id', 'client_id', 'BIGINT UNSIGNED');
            $this->renameColumnMySQL('appointments', 'servicio_id', 'service_id', 'BIGINT UNSIGNED');
            $this->renameColumnMySQL('appointments', 'personal_id', 'staff_id', 'BIGINT UNSIGNED');
            $this->renameColumnMySQL('appointments', 'empleado_id', 'employee_id', 'BIGINT UNSIGNED');
            $this->renameColumnMySQL('appointments', 'fecha', 'date', 'DATE');
            $this->renameColumnMySQL('appointments', 'hora_inicio', 'start_time', 'TIME');
            $this->renameColumnMySQL('appointments', 'hora_fin', 'end_time', 'TIME');
            $this->renameColumnMySQL('appointments', 'estado', 'status', 'VARCHAR(255)');
            $this->renameColumnMySQL('appointments', 'observaciones', 'notes', 'TEXT');
            $this->renameColumnMySQL('appointments', 'comprobante', 'receipt', 'VARCHAR(255)');
        }

        // appointment_states
        if (Schema::hasTable('appointment_states')) {
            $this->renameColumnMySQL('appointment_states', 'cita_id', 'appointment_id', 'BIGINT UNSIGNED');
            $this->renameColumnMySQL('appointment_states', 'estado', 'status', 'VARCHAR(255)');
            $this->renameColumnMySQL('appointment_states', 'usuario_id', 'user_id', 'BIGINT UNSIGNED');
            $this->renameColumnMySQL('appointment_states', 'fecha_cambio', 'change_date', 'TIMESTAMP');
        }

        // news
        if (Schema::hasTable('news')) {
            $this->renameColumnMySQL('news', 'titulo', 'title', 'VARCHAR(255)');
            $this->renameColumnMySQL('news', 'contenido', 'content', 'LONGTEXT');
            $this->renameColumnMySQL('news', 'imagen', 'image', 'VARCHAR(255)');
            $this->renameColumnMySQL('news', 'fecha_publicacion', 'publication_date', 'DATE');
            $this->renameColumnMySQL('news', 'publicada', 'published', 'TINYINT(1)');
            $this->renameColumnMySQL('news', 'usuario_id', 'user_id', 'BIGINT UNSIGNED');
        }

        // promotions
        if (Schema::hasTable('promotions')) {
            $this->renameColumnMySQL('promotions', 'titulo', 'title', 'VARCHAR(255)');
            $this->renameColumnMySQL('promotions', 'descripcion', 'description', 'TEXT');
            $this->renameColumnMySQL('promotions', 'descuento', 'discount', 'DECIMAL(5,2)');
            $this->renameColumnMySQL('promotions', 'fecha_inicio', 'start_date', 'DATE');
            $this->renameColumnMySQL('promotions', 'fecha_fin', 'end_date', 'DATE');
            $this->renameColumnMySQL('promotions', 'publicada', 'published', 'TINYINT(1)');
        }

        // pages
        if (Schema::hasTable('pages')) {
            $this->renameColumnMySQL('pages', 'titulo', 'title', 'VARCHAR(255)');
            $this->renameColumnMySQL('pages', 'contenido', 'content', 'LONGTEXT');
            $this->renameColumnMySQL('pages', 'publicada', 'published', 'TINYINT(1)');
        }

        // imports
        if (Schema::hasTable('imports')) {
            $this->renameColumnMySQL('imports', 'tipo', 'type', 'VARCHAR(255)');
            $this->renameColumnMySQL('imports', 'archivo', 'file', 'VARCHAR(255)');
            $this->renameColumnMySQL('imports', 'estado', 'status', 'VARCHAR(255)');
            $this->renameColumnMySQL('imports', 'importado_en', 'imported_at', 'TIMESTAMP');
        }

        // scheduled_tasks
        if (Schema::hasTable('scheduled_tasks')) {
            $this->renameColumnMySQL('scheduled_tasks', 'nombre', 'name', 'VARCHAR(255)');
            $this->renameColumnMySQL('scheduled_tasks', 'descripcion', 'description', 'TEXT');
            $this->renameColumnMySQL('scheduled_tasks', 'frecuencia', 'frequency', 'VARCHAR(255)');
            $this->renameColumnMySQL('scheduled_tasks', 'ultima_ejecucion', 'last_execution', 'TIMESTAMP');
        }
    }

    /**
     * Renombrar columna en MySQL usando ALTER TABLE
     */
    private function renameColumnMySQL($table, $oldName, $newName, $type): void
    {
        if (Schema::hasTable($table) && Schema::hasColumn($table, $oldName) && !Schema::hasColumn($table, $newName)) {
            DB::statement("ALTER TABLE `{$table}` CHANGE `{$oldName}` `{$newName}` {$type}");
        }
    }

    /**
     * Método estándar para otras bases de datos (no implementado completamente)
     */
    private function renameColumnsStandard(): void
    {
        // Para PostgreSQL, SQLite, etc. se necesitaría implementar de manera diferente
        // Por ahora solo soportamos MySQL
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios (implementar si es necesario)
    }
};
