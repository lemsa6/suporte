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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('cnpj')->unique(); // CNPJ da empresa
            $table->string('company_name'); // Nome da empresa
            $table->string('trade_name')->nullable(); // Nome fantasia
            $table->text('address')->nullable(); // Endereço
            $table->string('phone')->nullable(); // Telefone principal
            $table->string('email')->nullable(); // Email principal
            $table->text('notes')->nullable(); // Observações
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
