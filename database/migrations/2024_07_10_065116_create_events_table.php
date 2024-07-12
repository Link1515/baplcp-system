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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_group_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('sub_title');
            $table->integer('price');
            $table->timestamp('start_at');
            $table->timestamp('register_start_at');
            $table->timestamp('register_end_at');
            $table->integer('member_participants');
            $table->integer('non_member_participants');
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
