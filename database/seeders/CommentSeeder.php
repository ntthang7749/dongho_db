<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo thêm tài khoản người dùng mẫu nếu chưa tồn tại để phần bình luận đa dạng
        $users = [];
        $userData = [
            [
                'email' => 'binh@dongho.com',
                'name' => 'Trần Thị Bình',
                'username' => 'tranbinh',
            ],
            [
                'email' => 'cuong@dongho.com',
                'name' => 'Lê Văn Cường',
                'username' => 'vancuong',
            ],
            [
                'email' => 'hoang@dongho.com',
                'name' => 'Phạm Minh Hoàng',
                'username' => 'minhhoang',
            ],
            [
                'email' => 'thao@dongho.com',
                'name' => 'Hoàng Thu Thảo',
                'username' => 'thuthao',
            ],
            [
                'email' => 'bao@dongho.com',
                'name' => 'Đỗ Quốc Bảo',
                'username' => 'quocbao',
            ],
        ];

        foreach ($userData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => Hash::make('Customer@123'),
                    'role' => 'customer',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $users[] = $user;
        }

        // Thêm cả user customer test mặc định của hệ thống
        $defaultCustomer = User::where('email', 'customer@dongho.com')->first();
        if ($defaultCustomer) {
            $users[] = $defaultCustomer;
        }

        // 2. Danh sách bình luận tương ứng theo chủ đề bài viết cực kỳ thực tế
        $commentMapping = [
            'Top 10 Đồng Hồ Casio Bán Chạy Nhất 2024' => [
                'Mẫu G-Shock GA-2100 đẹp dã man, đeo ôm tay lắm.',
                'Có mẫu Casio Edifice EFV-620 ở cửa hàng không ạ? Mình muốn qua lên tay thử.',
                'Đang đeo G-Shock GW-M5610, pin mặt trời dùng cực sướng, không lo hết pin.',
                'Casio thì huyền thoại bền bỉ rồi, con đầu tiên mua 5 năm vẫn chạy tốt.',
                'Cửa hàng có ship COD đi Đà Nẵng không ạ? Muốn mua tặng em trai một chiếc G-Shock.',
            ],
            'Hướng Dẫn Chọn Đồng Hồ Phù Hợp Với Phong Cách' => [
                'Bài viết chi tiết quá, trước giờ toàn đeo đồng hồ thể thao đi làm công sở, nay phải sắm thêm chiếc dress watch rồi.',
                'Cổ tay 16cm thì nên đeo size mặt bao nhiêu là đẹp nhất vậy shop?',
                'Citizen Eco-Drive đeo đi làm công sở lịch sự sang trọng lắm, mình đang sở hữu một chiếc.',
                'Tư vấn giúp mình mẫu đồng hồ hợp với phong cách vintage cổ điển xíu nha.',
                'Bài viết rất bổ ích cho những người mới bắt đầu chơi đồng hồ như mình.',
            ],
            'Cách Bảo Quản Đồng Hồ Đúng Cách — Dùng Bền 10 Năm' => [
                'Cảm ơn shop đã chia sẻ, mình hay có thói quen để đồng hồ gần máy tính, từ nay phải cẩn thiện hơn.',
                'Đồng hồ cơ thì bao lâu nên đi lau dầu một lần vậy ạ? Chi phí có cao không?',
                'Dây da bị dính nước thì xử lý sao để không bị hôi hả shop ơi?',
                'Đúng là của bền tại người, chiếc Seiko của bố mình đeo hơn 15 năm vẫn bóng loáng nhờ bảo quản kỹ.',
                'Mẹo dùng bàn chải mềm vệ sinh dây thép rất hiệu quả nha mọi người, mình mới thử xong sạch bong.',
            ],
            'Seiko vs Citizen — Đồng Hồ Nhật Nào Tốt Hơn?' => [
                'Cá nhân mình vẫn chuộng máy cơ của Seiko hơn, Citizen Eco-Drive tuy tiện nhưng không có linh hồn bằng cơ.',
                'Nhưng Citizen Eco-Drive thì thực sự tiện lợi cho người lười đeo đồng hồ cơ như mình, lúc nào cũng chính xác.',
                'Mới tậu em Seiko 5 Sports tại shop, máy chạy êm, thiết kế trẻ trung đeo rất ưng ý.',
                'Vote 1 phiếu cho Citizen, dòng mỏng nhẹ công sở của Citizen đeo thoải mái lắm.',
                'Cả hai hãng đều là niềm tự hào của Nhật Bản, bền bỉ vô đối trong tầm giá.',
            ],
            'Đồng Hồ Tissot — Sang Trọng Thụy Sĩ Trong Tầm Tay' => [
                'Tissot Le Locle đúng là huyền thoại, thiết kế cổ điển không bao giờ lỗi mốt.',
                'Giá tầm 10 triệu thì Tissot là lựa chọn Thụy Sĩ tốt nhất rồi, kính sapphire sáng loáng.',
                'Bên mình có sẵn mẫu Tissot PR 100 Chronograph không ạ?',
                'Thương hiệu Thụy Sĩ có khác, độ hoàn thiện chi tiết kim và mặt số sắc sảo hơn hẳn đồng hồ Nhật.',
                'Mua đồng hồ Tissot tại shop có được bảo hành chính hãng toàn cầu không vậy?',
            ],
            'Xu Hướng Đồng Hồ 2024 — Thiết Kế Nào Đang Hot?' => [
                'Xu hướng mặt số xanh lục (green dial) năm nay hot thật sự, hãng nào cũng ra mắt mẫu mới.',
                'Mình rất thích phong cách skeleton lộ cơ, trông nghệ thuật và cơ khí cực kỳ.',
                'Mẫu G-Shock GA-2100 dây cao su vỏ kim loại (Oak) đang cháy hàng khắp nơi.',
                'Năm nay chuộng đồng hồ retro size nhỏ 36-38mm, đeo gọn gàng thanh lịch.',
                'Shop có sẵn mẫu nào mặt xanh navy dây ceramic không, tư vấn giúp mình với.',
            ],
        ];

        // 3. Thực hiện seed bình luận cho từng bài viết
        $newsList = News::all();
        $totalComments = 0;

        foreach ($newsList as $news) {
            // Tìm danh sách bình luận phù hợp dựa vào tiêu đề bài viết
            $commentsText = $commentMapping[$news->title] ?? null;

            // Nếu không khớp trực tiếp tiêu đề, lấy ngẫu nhiên 5 bình luận từ toàn bộ kho bình luận
            if (!$commentsText) {
                $allCommentsPool = array_merge(...array_values($commentMapping));
                shuffle($allCommentsPool);
                $commentsText = array_slice($allCommentsPool, 0, 5);
            }

            // Seed đúng 5 bình luận
            foreach ($commentsText as $index => $text) {
                // Phân phối ngẫu nhiên user viết bình luận
                $user = $users[$index % count($users)];

                Comment::create([
                    'news_id' => $news->id,
                    'user_id' => $user->id,
                    'content' => $text,
                    'status' => 'approved',
                    'is_spam' => false,
                    'created_at' => now()->subDays(rand(1, 15))->subHours(rand(1, 23)),
                ]);
                $totalComments++;
            }
        }

        $this->command->info("✅ Đã seed thành công {$totalComments} bình luận cho các bài viết!");
    }
}
