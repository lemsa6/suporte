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
        // Primeiro, adicionar a coluna hash se não existir
        if (!Schema::hasColumn('tickets', 'hash')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('hash', 32)->nullable()->after('ticket_number');
            });
        }
        
        // Adicionar hashes únicos para tickets existentes que não têm hash
        $this->addHashesToExistingTickets();
        
        // Verificar se o índice único já existe antes de criar
        $indices = DB::select("SHOW INDEX FROM tickets WHERE Key_name = 'tickets_hash_unique'");
        if (empty($indices)) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('hash', 32)->unique()->change();
            });
        }
        
        // Verificar se o índice hash já existe antes de criar
        $indices = DB::select("SHOW INDEX FROM tickets WHERE Key_name = 'tickets_hash_index'");
        if (empty($indices)) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->index('hash');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['hash']);
            $table->string('hash', 32)->nullable()->change();
        });
    }
    
    /**
     * Adiciona hashes únicos para tickets existentes
     */
    private function addHashesToExistingTickets(): void
    {
        $tickets = DB::table('tickets')->whereNull('hash')->orWhere('hash', '')->get();
        
        foreach ($tickets as $ticket) {
            do {
                $hash = bin2hex(random_bytes(16));
            } while (DB::table('tickets')->where('hash', $hash)->exists());
            
            DB::table('tickets')
                ->where('id', $ticket->id)
                ->update(['hash' => $hash]);
        }
    }
};
