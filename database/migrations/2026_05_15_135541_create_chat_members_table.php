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
        Schema::create('chat_members', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chat_id')
                ->constrained('chats')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('role', ['owner', 'member'])->default('member');

            $table->timestamp('joined_at')->nullable();

            $table->unsignedBigInteger('last_read_message_id')->nullable();

            $table->boolean('is_muted')->default(false);
            $table->boolean('is_archived')->default(false);

            $table->timestamps();

            $table->unique(['chat_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_members');
    }
};