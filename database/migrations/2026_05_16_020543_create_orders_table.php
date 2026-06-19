<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();          // VD: DH2024001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');

            // Thông tin giao hàng
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address');

            // Tiền
            $table->decimal('subtotal', 12, 0);             // tiền trước giảm giá
            $table->decimal('discount', 12, 0)->default(0); // số tiền được giảm
            $table->decimal('total', 12, 0);                // tiền cuối cùng

            // Thanh toán
            $table->enum('payment_method', ['cod', 'vnpay'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('vnpay_transaction_id')->nullable(); // mã GD VNPay

            // Trạng thái đơn hàng
            $table->enum('status', [
                'pending',      // chờ xác nhận
                'confirmed',    // đã xác nhận
                'shipping',     // đang giao
                'delivered',    // đã giao
                'cancelled',     // đã huỷ
            ])->default('pending');

            $table->text('note')->nullable();               // ghi chú của khách
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
