<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $categories = [
            ['name' => 'زيت', 'description' => 'أنواع زيوت المحركات'],
            ['name' => 'فلتر', 'description' => 'فلاتر الزيت، الهواء، الوقود، الكابن'],
            ['name' => 'فرامل', 'description' => 'أقراص، بطانات، سوائل فرامل'],
            ['name' => 'إطارات', 'description' => 'إطارات وعجلات'],
            ['name' => 'مستشعرات', 'description' => 'مستشعرات المحرك، ABS، O2، وغيرها'],
            ['name' => 'بطارية', 'description' => 'بطاريات السيارات'],
            ['name' => 'مكيف', 'description' => 'أجزاء نظام التكييف'],
            ['name' => 'إضاءة', 'description' => 'مصابيح أمامية، خلفية، داخلية'],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
