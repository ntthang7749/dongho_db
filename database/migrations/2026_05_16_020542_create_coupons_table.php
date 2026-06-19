<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();               // VD: SALE20
            $table->enum('type', ['percent', 'fixed']);     // % hoặc tiền cố định
            $table->decimal('value', 12, 0);                // VD: 20 (%) hoặc 50000 (VNĐ)
            $table->decimal('min_order', 12, 0)->default(0); // đơn tối thiểu
            $table->decimal('max_discount', 12, 0)->nullable(); // giảm tối đa
            $table->integer('max_usage')->default(1);       // tổng số lần dùng
            $table->integer('usage_count')->default(0);     // đã dùng bao nhiêu lần
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
