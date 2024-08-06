<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\JobVacancy;
use Closure;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\UserApplyJob;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Admin
        $this->command->warn(PHP_EOL . 'Creating admin user...');
        $user = $this->withProgressBar(1, fn () => User::factory(1)->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]));
        $this->command->info('Admin user created.');

        //Category
        $this->command->warn(PHP_EOL . 'Creating Categories...');
        $categories = $this->withProgressBar(6, fn () => Category::factory()->count(1)->create());
        $this->command->info('Categories created.');

        //Job Vacancies
        $this->command->warn(PHP_EOL . 'Creating Job Vacancies...');
        $jobVacancy = $this->withProgressBar(1, fn () => $categories->each(function ($category) {
            JobVacancy::factory()->count(rand(1, 2))->create([
                'category_id' => $category->id, // Mengaitkan JobVacancy dengan Category
            ]);
        }));
        $this->command->info('Job Vacancies created.');

        //User Apply Job
        $this->command->warn(PHP_EOL. "Creating User Apply Job...");
        $getOne = $jobVacancy->random();
        $this->withProgressBar(2, fn () => UserApplyJob::factory(1)->create([
            'job_vacancy_id' => $getOne->id,
        ]));
        $this->command->info('User Apply Job created.');
    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection();

        foreach (range(1, $amount) as $i) {
            $items = $items->merge(
                $createCollectionOfOne()
            );
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}
