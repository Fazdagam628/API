<?php

namespace Database\Seeders;

use App\Models\Regional;
use App\Models\Society;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Society::create([
            'id_card_number' => '1234567890',
            'password' => Hash::make('password123'),
            'name' => 'Siti Puspita',
            'born_date' => '1974-10-22',
            'gender' => 'female',
            'address' => 'kl.Raya setiabudi no.700',
            'regional' => 'semarang'
        ]);
    }
}
