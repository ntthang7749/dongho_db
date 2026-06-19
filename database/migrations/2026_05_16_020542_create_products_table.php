<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 0);              // giá gốc (VNĐ)
            $table->decimal('sale_price', 12, 0)->nullable(); // giá khuyến mãi
            $table->integer('stock')->default(0);          // tồn kho
            $table->string('thumbnail')->nullable();        // ảnh đại diện chính
            $table->string('sku')->unique()->nullable();    // mã sản phẩm

            // Thông số kỹ thuật đồng hồ
            $table->string('material')->nullable();         // chất liệu vỏ
            $table->string('glass_material')->nullable();   // kính sapphire...
            $table->string('band_material')->nullable();    // dây da/kim loại
            $table->string('water_resistance')->nullable(); // chống nước ATM
            $table->string('movement')->nullable();         // máy: quartz/automatic
            $table->string('case_size')->nullable();        // kích thước mặt (mm)
            $table->string('color')->nullable();            // màu sắc

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // nổi bật trang chủ
            $table->integer('view_count')->default(0);      // lượt xem
            $table->text('ai_description')->nullable();     // mô tả do AI tạo

            // Thống kê đánh giá (cache lại để query nhanh)
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
