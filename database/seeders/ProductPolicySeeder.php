<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policies = [
            0 => [
                'title' => 'Whether customers can return products within a period',
                'slug' => strtolower(str_replace(' ', '-', 'Whether customers can return products within a period')),
            ],
            1 => [
                'title' => 'Explains refund eligibility and process.',
                'slug' => strtolower(str_replace(' ', '-', 'Explains refund eligibility and process.')),
            ],
            2 => [
                'title' => 'Covers defects or damage for a set period.',
                'slug' => strtolower(str_replace(' ', '-', 'Covers defects or damage for a set period.')),
            ],
            3 => [
                'title' => 'Allows exchanging for a different size/color.',
                'slug' => strtolower(str_replace(' ', '-', 'Allows exchanging for a different size/color.')),
            ],
            4 => [
                'title' => 'Explains delivery timelines and charges',
                'slug' => strtolower(str_replace(' ', '-', 'Explains delivery timelines and charges')),
            ],
            5 => [
                'title' => 'Whether COD is available.',
                'slug' => strtolower(str_replace(' ', '-', 'Whether COD is available.')),
            ],
            6 => [
                'title' => 'Defines if and when an order can be canceled.',
                'slug' => strtolower(str_replace(' ', '-', 'Defines if and when an order can be canceled.')),
            ],
            7 => [
                'title' => 'How customer data is collected and used.',
                'slug' => strtolower(str_replace(' ', '-', 'How customer data is collected and used.')),
            ],
            8 => [
                'title' => 'If defective, the product will be replaced.',
                'slug' => strtolower(str_replace(' ', '-', 'If defective, the product will be replaced.')),
            ],
            9 => [
                'title' => 'Conditions under which free delivery applies.',
                'slug' => strtolower(str_replace(' ', '-', 'Conditions under which free delivery applies.')),
            ]
        ];

        DB::table('product_policies')->insert($policies);
    }
}
