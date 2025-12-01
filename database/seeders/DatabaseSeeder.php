<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'arief',
            'sekolah' => 'SMKN 2 Tasikmalaya',
            'rfid' => '2146798597',
            'password' => bcrypt('password'),
        ]);
    }
}
