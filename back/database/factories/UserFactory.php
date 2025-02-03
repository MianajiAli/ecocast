<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->unique()->phoneNumber,
            'confirm_code' => $this->faker->optional()->word,
            'phone_verified_at' => $this->faker->optional()->dateTime(),
            'password' => bcrypt('password123'), // Default password
            'role' => $this->faker->randomElement(['user', 'admin', 'author', 'superadmin']),
        ];
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is an author.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function author()
    {
        return $this->state([
            'role' => 'author',
        ]);
    }

    /**
     * Indicate that the user is a superadmin.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function superadmin()
    {
        return $this->state([
            'role' => 'superadmin',
        ]);
    }
}
