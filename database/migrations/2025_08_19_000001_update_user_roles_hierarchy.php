<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adicionar nova coluna temporária
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role_new', ['admin', 'tecnico', 'cliente_gestor', 'cliente_funcionario'])->default('cliente_funcionario')->after('role');
        });
        
        // Copiar dados com mapeamento
        DB::statement("UPDATE users SET role_new = CASE 
            WHEN role = 'admin' THEN 'admin'
            WHEN role = 'tecnico' THEN 'tecnico'
            WHEN role = 'cliente' THEN 'cliente_funcionario'
            ELSE 'cliente_funcionario'
        END");
        
        // Remover coluna antiga
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        
        // Renomear nova coluna
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role_new', 'role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Adicionar coluna temporária para rollback
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role_old', ['admin', 'tecnico', 'cliente'])->default('cliente')->after('role');
        });
        
        // Copiar dados de volta
        DB::statement("UPDATE users SET role_old = CASE 
            WHEN role = 'admin' THEN 'admin'
            WHEN role = 'tecnico' THEN 'tecnico'
            WHEN role IN ('cliente_gestor', 'cliente_funcionario') THEN 'cliente'
            ELSE 'cliente'
        END");
        
        // Remover coluna nova
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        
        // Renomear coluna antiga
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role_old', 'role');
        });
    }
};
