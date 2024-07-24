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
        Schema::table('season_registrations', function (Blueprint $table) {
            $table->renameColumn('event_group_id', 'season_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('season_registrations', function (Blueprint $table) {
            $table->renameColumn('season_id', 'event_group_id');
        });
    }
};
