<?php

namespace Database\Factories;

use App\Models\JobVacancy;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobVacancy>
 */
class JobVacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = JobVacancy::class;

    public function definition(): array
    {
        return [
            'title' => $name = $this->faker->unique()->jobTitle(),
            'slug' => Str::slug($name),
            'description' => $this->faker->realText(),
            'work_hours' => $this->faker->randomElement(['Full-time', 'Part-time']),
            'location' => $this->faker->city(),
            'qualifications' => $this->faker->realText(),
            'valid_until' => $this->faker->date('Y-m-d'),
            'experience' => $this->faker->randomElement(['Junior', 'Intermediate', 'Senior']),
            'remote' => $this->faker->boolean(),
            'enable' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
