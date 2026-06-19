<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // VD: Đồng hồ nam
            $table->string('slug')->unique();                // VD: dong-ho-nam
            $table->unsignedBigInteger('parent_id')->nullable(); // danh mục cha (null = cấp 1)
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);       // thứ tự hiển thị
            $table->timestamps();

            // Khoá ngoại tự trỏ về chính nó (danh mục cha/con)
            $table->foreign('parent_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
