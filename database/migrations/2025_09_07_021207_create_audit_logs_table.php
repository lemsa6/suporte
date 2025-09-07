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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // created, updated, deleted, replied, closed, etc.
            $table->string('auditable_type'); // App\Models\Ticket, App\Models\TicketMessage, etc.
            $table->unsignedBigInteger('auditable_id'); // ID do registro
            $table->unsignedBigInteger('user_id')->nullable(); // Usuário que fez a ação
            $table->string('user_type')->nullable(); // admin, tecnico, cliente
            $table->string('ip_address')->nullable(); // IP do usuário
            $table->text('user_agent')->nullable(); // User Agent do navegador
            $table->json('old_values')->nullable(); // Valores anteriores
            $table->json('new_values')->nullable(); // Valores novos
            $table->text('description')->nullable(); // Descrição da ação
            $table->string('url')->nullable(); // URL onde a ação foi executada
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE
            $table->timestamps();
            
            // Índices para performance
            $table->index(['auditable_type', 'auditable_id']);
            $table->index('user_id');
            $table->index('event_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
