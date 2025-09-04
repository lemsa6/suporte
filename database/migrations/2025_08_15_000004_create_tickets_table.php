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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // Número do ticket
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained('client_contacts')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title'); // Título do problema
            $table->text('description'); // Descrição detalhada
            $table->enum('priority', ['baixa', 'média', 'alta'])->default('média');
            $table->enum('status', ['aberto', 'em_andamento', 'resolvido', 'fechado'])->default('aberto');
            $table->timestamp('opened_at')->useCurrent(); // Data de abertura
            $table->timestamp('resolved_at')->nullable(); // Data de resolução
            $table->timestamp('closed_at')->nullable(); // Data de fechamento
            $table->text('resolution_notes')->nullable(); // Notas da resolução
            $table->boolean('is_urgent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
