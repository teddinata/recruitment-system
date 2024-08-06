<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserApplyJob>
 */
class UserApplyJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->unique()->phoneNumber(),
            'address' => $this->faker->unique()->address(),
            'place_of_birth' => $this->faker->city(),
            'date_of_birth' => $this->faker->dateTimeBetween('1990-01-01', '2012-12-31'),
            'education' => $this->faker->randomElement(['SMA/SMK', 'D3', 'S1', 'S2']),
            'major' => $this->faker->randomElement(['Teknik Informatika', 'Sistem Informasi', 'Ekonomi Pembangunan', 'Teknik Otomotif']),
            'join_date' => $this->faker->randomElement(['1 Hari', '2 Hari', '3 Hari', '4 Hari']),
            'linkedin_url' => 'https://www.' . $this->faker->domainName(),
            'job_source' => $this->faker->randomElement(['Facebook', 'Twitter', 'Instagram']),
            'old_company' => $this->faker->company(),
            'self_description' => $this->faker->paragraph(),
            'gender' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
