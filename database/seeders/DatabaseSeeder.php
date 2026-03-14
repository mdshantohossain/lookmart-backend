<?php

namespace Database\Seeders;

use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ProductPolicySeeder::class);
        $this->call(RolePermissionsSeeder::class);
//
        $categories = [
            'Electronics' => [
                0 => ['name' => 'Mobile Phones'],
                1 => ['name' => 'Laptops & Computers'],
                2 => ['name' => 'Headphones'],
                3 => ['name' => 'Smart Home Devices'],
            ],
            'Fashion & Aparel' => [
                0 => ['name' => 'Men’s Clothing'],
                1 => ['name' => 'Women’s Clothing'],
                2 => ['name' => 'Footwear'],
                3 => ['name' => 'Accessories (Watches, Bags, Jewelry)'],
            ],
            'Home & Kichen' => [
                0 => ['name' => 'Furniture'],
                1 => ['name' => 'Appliances'],
                2 => ['name' => 'Cookware & Dining'],
                3 => ['name' => 'Bedding & Bath'],
            ],
            'Beauty & Peronal Care' => [
                0 => ['name' => 'Skincare'],
                1 => ['name' => 'Makeup'],
                2 => ['name' => 'Hair Care'],
                3 => ['name' => 'Fragrances'],
            ],
            'Health & Welness' => [
                0 => ['name' => 'Vitamins & Supplements'],
                1 => ['name' => 'Fitness Equipment'],
                2 => ['name' => 'Personal Care Devices'],
            ],
            'Toys & Gaes' => [
                0 => ['name' => 'Educational Toys'],
                1 => ['name' => 'Board Games'],
                2 => ['name' => 'Puzzles'],
                3 => ['name' => 'Outdoor Play Equipment'],
            ],
            'Sports & ' => [
                0 => ['name' => 'Sportswear'],
                1 => ['name' => 'Camping Gear'],
                2 => ['name' => 'Fitness Accessories'],
                3 => ['name' => 'Bicycles'],
            ],
            'Automotive' => [
                0 => ['name' => 'Car Accessories'],
                1 => ['name' => 'Tools & Equipment'],
                2 => ['name' => 'Tires & Wheels'],
            ],
            'Books & Stationery' => [
                0 => ['name' => 'Fiction & Non-Fiction'],
                1 => ['name' => 'Educational Books'],
                2 => ['name' => 'Office Supplies'],
                3 => ['name' => 'Art Supplies'],
            ],
        ];
//
        foreach ($categories as $categoryName => $subCategories) {
            $category = Category::create([
                'name' => $categoryName,
                'image' => "https://picsum.photos/id/". rand(250, 800) ."/200/300",
                'slug' => strtolower(Str::random(65)),
            ]);

            foreach ($subCategories as $subCategory)
            {
                SubCategory::create([
                    'category_id' => $category->id,
                    'name' =>  $subCategory['name'],
                    'image' => "https://picsum.photos/id/". rand(250, 800) ."/200/300",
                    'slug' => strtolower(Str::slug($subCategory['name'])),
                ]);
            }
        }
    }
}



