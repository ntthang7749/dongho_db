<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', [
                'chatbot',
                'suggest',
                'sentiment',
                'summary',
                'description',
                'spam_filter',
                'image_recognition',
            ]);
            $table->text('input');              // câu hỏi / input gửi lên AI
            $table->longText('output');         // câu trả lời AI
            $table->integer('tokens_used')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};
