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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_message_id')->constrained()->onDelete('cascade');
            $table->string('filename'); // Nome original do arquivo
            $table->string('file_path'); // Caminho no storage
            $table->string('file_type'); // Tipo MIME
            $table->bigInteger('file_size'); // Tamanho em bytes
            $table->string('disk')->default('local'); // Disco de storage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
