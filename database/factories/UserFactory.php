<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'first_name'         => $this->faker->firstName(),
            'last_name'          => $this->faker->lastName(),
            'email'              => $this->faker->unique()->safeEmail(),
            'email_verified_at'  => now(),
            'password'           => Hash::make('password'),
            'remember_token'     => Str::random(10),
            // Additional fields
            'image_url'          => function () {
                $name = $this->faker->firstName();
                return 'https://ui-avatars.com/api/?name=' . urlencode($name);
            },
            'background_url'          => function () {
                $name = $this->faker->firstName();
                return 'https://fakeimg.pl/800x400/?text=' . urlencode($name) . '&font=lobster';
            },
            'cv'                 => $this->faker->url(),
        ];
    }

    /**
     * Indicate that the user has the "user" role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function userRole()
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('user');
        });
    }

    /**
     * Indicate that the user has the "company" role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function companyRole()
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('company');
        });
    }
}
