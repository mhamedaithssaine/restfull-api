<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Catégories principales
        $webDev = Category::create([
            'name' => 'Développement Web',
            'parent_id' => null
        ]);

        $mobileDev = Category::create([
            'name' => 'Développement Mobile',
            'parent_id' => null
        ]);

        $dataScience = Category::create([
            'name' => 'Data Science',
            'parent_id' => null
        ]);

        // Sous-catégories pour Développement Web
        Category::create([
            'name' => 'Frontend',
            'parent_id' => $webDev->id
        ]);

        Category::create([
            'name' => 'Backend',
            'parent_id' => $webDev->id
        ]);

        Category::create([
            'name' => 'Fullstack',
            'parent_id' => $webDev->id
        ]);

        // Sous-catégories pour Développement Mobile
        Category::create([
            'name' => 'Android',
            'parent_id' => $mobileDev->id
        ]);

        Category::create([
            'name' => 'iOS',
            'parent_id' => $mobileDev->id
        ]);

        Category::create([
            'name' => 'React Native',
            'parent_id' => $mobileDev->id
        ]);

        // Sous-catégories pour Data Science
        Category::create([
            'name' => 'Machine Learning',
            'parent_id' => $dataScience->id
        ]);

        Category::create([
            'name' => 'Big Data',
            'parent_id' => $dataScience->id
        ]);
    }
}
