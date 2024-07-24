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
        Schema::table('event_group_registrations', function (Blueprint $table) {
            $table->boolean('pass')->default(false)->after('event_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_group_registrations', function (Blueprint $table) {
            $table->dropColumn('pass');
        });
    }
};
