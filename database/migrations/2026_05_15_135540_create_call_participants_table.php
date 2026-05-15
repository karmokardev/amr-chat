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
        Schema::create('call_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('call_room_id')
                ->constrained('call_rooms')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();

            $table->boolean('is_audio_enabled')->default(true);
            $table->boolean('is_video_enabled')->default(true);

            $table->string('status')->default('joined');

            $table->timestamps();

            $table->unique(['call_room_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_participants');
    }
};