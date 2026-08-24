<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $b1 = Brand::firstOrCreate(['slug' => 'beestyle-signature'], [
            'name' => 'BeeStyle Signature',
            'logo' => '/assets/img/icons/icon-1.png',
            'banner' => '/assets/img/generic/1.png',
            'description' => 'Dòng thời trang nam cao cấp độc quyền từ BeeStyle, thiết kế tinh xảo theo phong cách thanh lịch hiện đại.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $b2 = Brand::firstOrCreate(['slug' => 'bee-luxury-line'], [
            'name' => 'Bee Luxury Line',
            'logo' => '/assets/img/icons/icon-2.png',
            'banner' => '/assets/img/generic/2.png',
            'description' => 'Bộ sưu tập vest cưới, blazer dự tiệc và sơ mi lụa tơ tằm thượng hạng dành riêng cho quý ông lịch lãm.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $b3 = Brand::firstOrCreate(['slug' => 'bee-urban-casual'], [
            'name' => 'Bee Urban Casual',
            'logo' => '/assets/img/icons/icon-3.png',
            'banner' => '/assets/img/generic/3.png',
            'description' => 'Phong cách đường phố trẻ trung, tối giản, năng động dành cho các hoạt động thường ngày và dạo phố.',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $b4 = Brand::firstOrCreate(['slug' => 'bee-sport-tech'], [
            'name' => 'Bee Sport Tech',
            'logo' => '/assets/img/icons/icon-4.png',
            'banner' => '/assets/img/generic/4.png',
            'description' => 'Trang phục thể thao ứng dụng công nghệ làm mát Air-Cool và co giãn 4 chiều vận động tối ưu.',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        Product::where('id', '<=', 15)->update(['brand_id' => $b1->id]);
        Product::whereBetween('id', [16, 28])->update(['brand_id' => $b2->id]);
        Product::whereBetween('id', [29, 40])->update(['brand_id' => $b3->id]);
        Product::where('id', '>', 40)->update(['brand_id' => $b4->id]);
    }
}
