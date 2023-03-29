<?php

namespace Dystcz\LunarApi\Domain\Users\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = \Dystcz\LunarApi\Tests\Stubs\Users\User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
            'remember_token' => $this->faker->word,
        ];
    }
}
