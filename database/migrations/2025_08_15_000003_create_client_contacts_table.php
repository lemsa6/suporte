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
        Schema::create('client_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nome do funcionário
            $table->string('email')->unique(); // Email do funcionário
            $table->string('phone')->nullable(); // Telefone
            $table->string('position')->nullable(); // Cargo
            $table->string('department')->nullable(); // Departamento
            $table->boolean('is_primary')->default(false); // Contato principal
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_contacts');
    }
};
