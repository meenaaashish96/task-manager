<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample task for user ID 1 (adjust if needed)
        Task::create([
            'title' => 'Sample seeded task',
            'description' => 'This task was seeded to verify DB writes.',
            'status' => 'pending',
            'due_date' => now()->toDateString(),
            'user_id' => 1,
        ]);
    }
}


