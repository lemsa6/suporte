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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('created_ip')->nullable()->after('created_at');
            $table->text('created_user_agent')->nullable()->after('created_ip');
            $table->string('updated_ip')->nullable()->after('updated_at');
            $table->text('updated_user_agent')->nullable()->after('updated_ip');
            $table->string('closed_ip')->nullable()->after('closed_at');
            $table->text('closed_user_agent')->nullable()->after('closed_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'created_ip',
                'created_user_agent',
                'updated_ip',
                'updated_user_agent',
                'closed_ip',
                'closed_user_agent'
            ]);
        });
    }
};
