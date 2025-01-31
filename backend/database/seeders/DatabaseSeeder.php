<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'username1',
            'email' => 'test@example.com',
            'password' => Hash::make('qwerty123'),
        ]);
        User::factory()->create([
            'username' => 'username2',
            'email' => 'test2@example.com',
            'password' => Hash::make('qwerty123'),
        ]);
        Task::factory(12)->create([
            'owner_id' => 1,
        ]);
        Task::factory(5)->create([
            'owner_id' => 2,
        ]);
    }
}
