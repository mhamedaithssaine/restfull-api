<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $levels = ['beginner', 'intermediate', 'advanced'];

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(1),
            'content' => $this->faker->paragraphs(3, true),
            'cover' => $this->faker->imageUrl(),
            'duration' => $this->faker->numberBetween(30, 180),
            'level' => $this->faker->randomElement($levels),
            'category_id' => Category::factory(),
            'created_at' => now(),
            'updated_at' => now(),
            'user_id'=>2,
        ];
    }
}
