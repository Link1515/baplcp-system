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
        Schema::table('seasons', function (Blueprint $table) {
            $table->time('event_start_at')->after('total_participants');
            $table->time('event_end_at')->after('event_start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->dropColumn('event_start_at');
            $table->dropColumn('event_end_at');
        });
    }
};
