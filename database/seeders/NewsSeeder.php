<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $articles = [
            [
                'title' => 'Top 10 Đồng Hồ Casio Bán Chạy Nhất 2024',
                'summary' => 'Khám phá những mẫu đồng hồ Casio được yêu thích nhất trong năm 2024, từ G-Shock đến Edifice đều có trong danh sách.',
                'content' => "Casio là thương hiệu đồng hồ Nhật Bản được yêu thích tại Việt Nam với nhiều dòng sản phẩm đa dạng từ bình dân đến cao cấp.\n\n**1. Casio G-Shock GA-2100**\nMẫu đồng hồ G-Shock mỏng nhất với thiết kế analog-digital kết hợp. Chống va đập, chống nước 200m, pin 3 năm.\n\n**2. Casio Edifice EFV-620**\nDòng Edifice dành cho doanh nhân trẻ, thiết kế chronograph sang trọng, chống nước 100m.\n\n**3. Casio G-Shock GW-M5610**\nSử dụng năng lượng mặt trời, thu sóng vô tuyến tự động chỉnh giờ trên toàn thế giới.\n\nHãy ghé thăm cửa hàng để xem thêm nhiều mẫu đồng hồ Casio chính hãng với giá tốt nhất!",
            ],
            [
                'title' => 'Hướng Dẫn Chọn Đồng Hồ Phù Hợp Với Phong Cách',
                'summary' => 'Không biết chọn đồng hồ nào phù hợp? Bài viết này sẽ giúp bạn tìm ra chiếc đồng hồ hoàn hảo cho từng dịp.',
                'content' => "Đồng hồ không chỉ là công cụ xem giờ mà còn là phụ kiện thể hiện cá tính và phong cách của bạn.\n\n**Đồng hồ công sở**\nChọn những mẫu có thiết kế thanh lịch, mặt số không quá lớn (38-42mm), dây da hoặc dây thép. Các thương hiệu phù hợp: Tissot, Seiko Presage, Citizen Eco-Drive.\n\n**Đồng hồ thể thao**\nƯu tiên tính năng chống nước, chống va đập, chronograph. G-Shock và Seiko 5 Sports là lựa chọn hàng đầu.\n\n**Đồng hồ dạo phố**\nCó thể thoải mái hơn về thiết kế, màu sắc. Fossil, Casio Standard đều là những lựa chọn tốt.\n\n**Lưu ý khi chọn đồng hồ:**\n- Kích thước mặt phù hợp với cổ tay\n- Chức năng thực sự cần thiết\n- Ngân sách phù hợp\n- Bảo hành chính hãng",
            ],
            [
                'title' => 'Cách Bảo Quản Đồng Hồ Đúng Cách — Dùng Bền 10 Năm',
                'summary' => 'Những bí quyết giúp đồng hồ của bạn luôn bền đẹp và chạy chính xác sau nhiều năm sử dụng.',
                'content' => "Đồng hồ là vật dụng tinh tế cần được chăm sóc đúng cách để duy trì độ chính xác và tuổi thọ.\n\n**1. Tránh va đập mạnh**\nDù đồng hồ có khả năng chống va đập, hãy tránh để rơi hoặc đập mạnh vào các bề mặt cứng.\n\n**2. Tránh tiếp xúc với hóa chất**\nNước hoa, kem dưỡng da, hóa chất tẩy rửa có thể làm hỏng dây da và mạ kim loại.\n\n**3. Bảo quản khi không đeo**\nĐể trong hộp kèm túi hút ẩm. Tránh nơi có từ trường mạnh như loa, máy tính.\n\n**4. Vệ sinh định kỳ**\nLau sạch bằng vải mềm sau khi đeo. Đối với dây thép, có thể dùng bàn chải mềm và nước ấm.\n\n**5. Bảo dưỡng đồng hồ cơ**\nĐưa đến trung tâm bảo hành mỗi 3-5 năm để tra dầu và kiểm tra máy.\n\nĐồng hồ được chăm sóc tốt có thể sử dụng hàng chục năm và trở thành vật gia truyền quý giá!",
            ],
            [
                'title' => 'Seiko vs Citizen — Đồng Hồ Nhật Nào Tốt Hơn?',
                'summary' => 'So sánh chi tiết giữa hai thương hiệu đồng hồ Nhật Bản hàng đầu để giúp bạn đưa ra quyết định đúng đắn.',
                'content' => "Seiko và Citizen đều là hai thương hiệu đồng hồ Nhật Bản nổi tiếng toàn cầu. Mỗi thương hiệu có điểm mạnh riêng.\n\n**Seiko**\n- Lịch sử lâu đời từ 1881\n- Tự sản xuất 100% linh kiện trong nhà (Manufacture)\n- Nổi tiếng với đồng hồ cơ (Automatic) chất lượng cao\n- Dòng G-Shock... à nhầm, dòng Presage và Prospex rất được yêu thích\n- Giá từ 1.5 triệu đến hàng trăm triệu\n\n**Citizen**\n- Công nghệ Eco-Drive độc quyền (năng lượng ánh sáng)\n- Không bao giờ phải thay pin\n- Độ chính xác cao, bền bỉ\n- Thiết kế thanh lịch phù hợp công sở\n- Giá từ 2 triệu đến vài chục triệu\n\n**Kết luận:**\nNếu bạn thích đồng hồ cơ, hãy chọn Seiko. Nếu muốn tiện lợi không thay pin, Citizen Eco-Drive là lựa chọn hoàn hảo.",
            ],
            [
                'title' => 'Đồng Hồ Tissot — Sang Trọng Thụy Sĩ Trong Tầm Tay',
                'summary' => 'Tissot là cánh cửa vào thế giới đồng hồ Thụy Sĩ cao cấp với mức giá hợp lý. Khám phá các dòng sản phẩm hot nhất.',
                'content' => "Tissot được thành lập năm 1853 tại Le Locle, Thụy Sĩ. Đây là thương hiệu thuộc tập đoàn Swatch Group, nổi tiếng với khẩu hiệu \"Innovators by Tradition\".\n\n**Các dòng Tissot phổ biến:**\n\n**T-Touch Expert Solar**\nĐồng hồ thông minh với màn hình cảm ứng, la bàn, nhiệt kế, altimeter. Sử dụng năng lượng mặt trời.\n\n**PR 100**\nDòng đồng hồ thể thao thanh lịch, chống nước 100m, kính sapphire, giá tầm 9-12 triệu.\n\n**T-Classic Tradition**\nThiết kế cổ điển, mỏng nhẹ, phù hợp với phong cách lịch sự.\n\n**Lý do nên chọn Tissot:**\n- Swiss Made chính hãng\n- Kính sapphire tiêu chuẩn\n- Bảo hành 2 năm toàn cầu\n- Giá hợp lý so với chất lượng Thụy Sĩ",
            ],
            [
                'title' => 'Xu Hướng Đồng Hồ 2024 — Thiết Kế Nào Đang Hot?',
                'summary' => 'Cập nhật những xu hướng đồng hồ hot nhất năm 2024, từ màu sắc đến chất liệu và tính năng.',
                'content' => "Năm 2024 chứng kiến nhiều xu hướng thú vị trong thế giới đồng hồ. Hãy cùng điểm qua những điểm nổi bật!\n\n**1. Màu xanh navy và xanh lá**\nMặt số màu xanh navy và xanh lá (\"dial\") đang rất được ưa chuộng, từ đồng hồ thể thao đến dress watch.\n\n**2. Mặt số skeleton (lộ máy)**\nCác mẫu đồng hồ lộ máy cơ ngày càng phổ biến, thể hiện sự tinh tế trong chế tác.\n\n**3. Thiết kế mỏng và nhẹ**\nThị trường đang chứng kiến xu hướng đồng hồ siêu mỏng, tiêu biểu là G-Shock GA-2100.\n\n**4. Dây ceramic**\nDây ceramic (gốm) chống xước, không gây dị ứng, màu sắc đa dạng.\n\n**5. Kết hợp kim loại và cao su**\nTrend hybrid kết hợp vỏ kim loại với dây cao su thoải mái.\n\nHãy ghé Đồng Hồ Online để cập nhật những mẫu theo trend mới nhất!",
            ],
        ];

        foreach ($articles as $article) {
            $slug = Str::slug($article['title']).'-'.Str::random(5);

            News::updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'slug' => $slug,
                    'summary' => $article['summary'],
                    'content' => $article['content'],
                    'user_id' => $admin?->id ?? 1,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✅ Đã tạo '.count($articles).' bài viết!');
    }
}
