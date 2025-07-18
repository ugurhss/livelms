<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
 $categories = [
            [
                'name' => 'Web Geliştirme',
                'slug' => 'web-gelistirme',
            ],
            [
                'name' => 'Mobil Uygulama',
                'slug' => 'mobil-uygulama',
            ],
            [
                'name' => 'Veri Bilimi',
                'slug' => 'veri-bilimi',
            ],
            [
                'name' => 'Yapay Zeka',
                'slug' => 'yapay-zeka',
            ],
            [
                'name' => 'Siber Güvenlik',
                'slug' => 'siber-guvenlik',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
