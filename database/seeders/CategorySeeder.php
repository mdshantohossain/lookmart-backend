<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            0 => [
                'name' => 'Electronics',
                'image' => 'https://picsum.photos/id/237/200/300',
                'description' => 'Electronics',
            ],
            1 => [
                'name' => 'Fashion & Apparel',
                'image' => 'https://picsum.photos/id/238/200/300'
            ],
            2 => [
                'name' => 'Home & Kitchen',
                'image' => 'https://picsum.photos/id/239/200/300'
            ],
            3 => [
                'name' => 'Beauty & Personal Care',
                'image' => 'https://picsum.photos/id/240/200/300'
            ],
            4 => [
                'name' => 'Health & Wellness',
                'image' => 'https://picsum.photos/id/241/200/300'
            ],

            5 => [
                'name' => 'Toys & Games',
                'image' => 'https://picsum.photos/id/242/200/300'
            ],

            6 => [
                'name' => 'Sports & Outdoors',
                'image' => 'https://picsum.photos/id/243/200/300'
            ],

            7 => [
                'name' => 'Automotive',
                'image' => 'https://picsum.photos/id/244/200/300'
            ],

            8 => [
                'name' => 'Books & Stationery',
                'image' => 'https://picsum.photos/id/246/200/300'
            ],

            9 => [
                'name' => 'Pet Supplies',
                'image' => 'https://picsum.photos/id/247/200/300'
            ],
        ]);
    }
}
