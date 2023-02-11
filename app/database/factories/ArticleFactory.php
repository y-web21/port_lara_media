<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $titlePre = ['news', '記事', '社会風刺', '新着', 'new着', '新s', '風説の流布', '概要'];
        return [
            'title' => fake()->randomElement($titlePre) . '-' . $this->faker->randomNumber(4),
            'content' => fake()->realText(1000),
            'author' => random_int(1, 4),
            'updated_by' => random_int(1, 4),
            'status' => random_int(0, 1),
        ];
    }
}
