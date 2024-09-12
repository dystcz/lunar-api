<?php

namespace Dystcz\LunarApi\Domain\Users\Factories;

use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password123'),
            'remember_token' => $this->faker->word,
        ];
    }
}
