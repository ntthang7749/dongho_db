<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->text('summary')->nullable();         // tóm tắt ngắn
            $table->longText('content');                 // nội dung đầy đủ
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // tác giả
            $table->boolean('is_active')->default(true);
            $table->text('ai_suggested_title')->nullable(); // tiêu đề AI gợi ý
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
