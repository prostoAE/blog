<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->sentence,
            'image' => 'photo1.png',
            'date' => '08/09/17',
            'views' => $this->faker->numberBetween(0, 5000),
            'category_id' => 1,
            'user_id' => 1,
            'status' => 1,
            'is_featured' => 0,
        ];
    }
}
