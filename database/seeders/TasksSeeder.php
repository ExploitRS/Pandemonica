<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $count = 10): void
    {
        Task::factory()
            ->count($count)
            ->create([
                'due_date' => '2045-01-01',
            ]);
    }
}
