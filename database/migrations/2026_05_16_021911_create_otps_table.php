<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp', 6);                           // mã 6 số
            $table->enum('type', ['register', 'reset_password']); // loại OTP
            $table->timestamp('expires_at');                    // hết hạn sau 10 phút
            $table->boolean('is_used')->default(false);         // đã dùng chưa
            $table->timestamps();

            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
