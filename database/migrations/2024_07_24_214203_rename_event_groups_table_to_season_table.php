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
        Schema::rename('event_groups', 'seasons');
        Schema::rename('event_group_registrations', 'season_registrations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('seasons', 'event_groups');
        Schema::rename('season_registrations', 'event_group_registrations');
    }
};
