<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_dialog_states', function (Blueprint $table) {
            $table->id();
            $table->string('bot_name');
            $table->string('chat_id');
            $table->unsignedBigInteger('user_id');
            $table->string('handler');
            $table->string('step');
            $table->json('answers');
            $table->timestamp('last_touched_at')->nullable();
            $table->timestamps();

            $table->unique(['bot_name', 'chat_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_dialog_states');
    }
};
