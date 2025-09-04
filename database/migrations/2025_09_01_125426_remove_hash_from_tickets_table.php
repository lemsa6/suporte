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
        // Remover hash se existir
        if (Schema::hasColumn('tickets', 'hash')) {
            Schema::table('tickets', function (Blueprint $table) {
                try {
                    $table->dropIndex('tickets_hash_unique');
                } catch (\Exception $e) {
                    // Índice não existe, ignorar
                }
                $table->dropColumn('hash');
            });
        }
        
        // Verificar se ticket_number existe e é único
        if (!Schema::hasColumn('tickets', 'ticket_number')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('ticket_number')->after('id');
            });
        }
        
        // Tentar adicionar índice único
        try {
            Schema::table('tickets', function (Blueprint $table) {
                $table->unique('ticket_number');
            });
        } catch (\Exception $e) {
            // Índice já existe, ignorar
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Adicionar hash novamente se necessário
            if (!Schema::hasColumn('tickets', 'hash')) {
                $table->string('hash')->nullable()->after('ticket_number');
                $table->unique('hash');
            }
        });
    }
};
