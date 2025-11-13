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
        Schema::table('client_contacts', function (Blueprint $table) {
            // Adicionar foreign key direta para users
            $table->foreignId('user_id')->nullable()->after('client_id')->constrained('users')->onDelete('set null');
            
            // Criar índice para performance
            $table->index('user_id', 'idx_client_contacts_user_id');
        });
        
        // Migrar dados existentes: sincronizar user_id baseado no email
        DB::statement("
            UPDATE client_contacts cc 
            SET user_id = (
                SELECT u.id 
                FROM users u 
                WHERE u.email = cc.email 
                LIMIT 1
            )
            WHERE EXISTS (
                SELECT 1 
                FROM users u 
                WHERE u.email = cc.email
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_contacts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex('idx_client_contacts_user_id');
            $table->dropColumn('user_id');
        });
    }
};
