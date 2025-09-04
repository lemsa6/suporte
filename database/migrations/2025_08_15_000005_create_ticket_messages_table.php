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
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('contact_id')->nullable()->constrained('client_contacts')->onDelete('set null');
            $table->enum('type', ['note', 'reply', 'status_change', 'assignment'])->default('reply');
            $table->text('message'); // Conteúdo da mensagem
            $table->json('metadata')->nullable(); // Dados adicionais (status anterior, novo status, etc.)
            $table->boolean('is_internal')->default(false); // Mensagem interna da equipe
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
