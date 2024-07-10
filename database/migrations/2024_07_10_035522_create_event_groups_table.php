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
        Schema::create('event_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sub_title');
            $table->boolean('enabled');
            $table->boolean('can_register_all');
            $table->integer('price');
            $table->timestamp('register_start_datetime')->nullable();
            $table->timestamp('register_end_datetime')->nullable();
            $table->integer('max_participants');
            $table->foreignId('previous_event_group_id')->nullable()->constrained('event_groups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_groups');
    }
};
