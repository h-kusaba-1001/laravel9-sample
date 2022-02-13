<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminUser>
 */
class AdminUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'login_id' => $this->faker->slug(),
            'name' => $this->faker->name(),
            'password' => Hash::make('password'),
            'is_active' => $this->faker->randomElement([true, false]),
        ];
    }
}
