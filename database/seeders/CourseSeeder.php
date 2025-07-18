<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $instructors = User::where('role', 'instructor')->get();
        $statuses = ['draft', 'published', 'archived'];
        $levels = ['beginner', 'intermediate', 'advanced'];
        $categories = Category::pluck('name')->toArray();

        foreach ($instructors as $instructor) {
            for ($i = 1; $i <= 5; $i++) {
                Course::create([
                    'title' => $instructor->name . ' Kurs ' . $i,
                    'slug' => strtolower(str_replace(' ', '-', $instructor->name)) . '-kurs-' . $i,
                    'description' => 'Bu kurs ' . $instructor->name . ' tarafından verilen harika bir kurstur.',
                    'user_id' => $instructor->id,
                    'category' => $categories[array_rand($categories)],  // string kategori adı
                    'level' => $levels[array_rand($levels)],              // enum sütun adı level
                    'status' => $statuses[array_rand($statuses)],
                    'thumbnail' => 'https://picsum.photos/800/400?random=' . $i,
                    'price' => rand(100, 1000),                            // 0-1000 arası rastgele fiyat
                    'original_price' => rand(1000, 2000),                  // orijinal fiyat
                    'outcomes' => json_encode(['Skill 1', 'Skill 2']),     // basit örnek JSON
                    'prerequisites' => json_encode(['Prerequisite 1']),   // basit örnek JSON
                ]);
            }
        }
    }
}
