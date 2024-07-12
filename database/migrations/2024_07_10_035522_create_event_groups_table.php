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
            $table->boolean('enabled')->default(true);
            $table->integer('price');
            $table->integer('member_participants');
            $table->integer('non_member_participants');

            $table->boolean('can_register_all_event');
            $table->timestamp('register_start_at')->nullable();
            $table->timestamp('register_end_at')->nullable();
            $table->integer('register_all_participants')->nullable();
            $table->integer('register_all_price')->nullable();

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
