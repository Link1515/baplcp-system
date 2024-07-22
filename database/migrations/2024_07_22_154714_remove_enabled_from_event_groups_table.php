<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_groups', function (Blueprint $table) {
            $table->removeColumn('enabled');
            $table->renameColumn('member_participants', 'total_participants');
            $table->renameColumn('can_register_all_event', 'can_register_all_events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_groups', function (Blueprint $table) {
            $table->boolean('enabled')->default(true);
            $table->renameColumn('total_participants', 'member_participants');
            $table->renameColumn('can_register_all_events', 'can_register_all_event');
        });
    }
};
