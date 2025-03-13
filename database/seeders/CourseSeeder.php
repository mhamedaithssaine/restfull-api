<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Récupérer toutes les catégories et tags
        $categories = Category::all();
        $tags = Tag::all();

        // Créer 20 cours et attacher des tags aléatoires
        Course::factory(20)->create()->each(function ($course) use ($tags) {
            // Attacher 2 à 4 tags aléatoires à chaque cours
            $course->tags()->attach(
                $tags->random(rand(2, 4))->pluck('id')->toArray()
            );
        });
    }
}