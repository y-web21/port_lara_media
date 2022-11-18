<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * randVerifiedDate
     *
     * @param  int $days RAND_MAX value
     * @return self
     */
    public function randVerifiedDate(int $days = 7): self
    {
        return $this->state(function () use ($days) {
            return [
                'email_verified_at' => Carbon::now()->addMinutes(-(mt_rand(0, 60 * 24 * $days)))
            ];
        });
    }

    /**
     * @param string $pass password string
     * @return self
     */
    public function setPass(string $pass = ''): self
    {
        return $this->state([
            'password' => Hash::make($pass),
        ]);
    }

    /**
     * for dev and test
     *
     * @return self
     */
    public function unsafePass(): self
    {
        return $this->setPass('pass');
    }
}
