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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_ticket_created')->default(true)->after('is_active');
            $table->boolean('notify_ticket_replied')->default(true)->after('notify_ticket_created');
            $table->boolean('notify_ticket_status_changed')->default(true)->after('notify_ticket_replied');
            $table->boolean('notify_ticket_closed')->default(true)->after('notify_ticket_status_changed');
            $table->boolean('notify_ticket_priority_changed')->default(false)->after('notify_ticket_closed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notify_ticket_created',
                'notify_ticket_replied',
                'notify_ticket_status_changed',
                'notify_ticket_closed',
                'notify_ticket_priority_changed'
            ]);
        });
    }
};
