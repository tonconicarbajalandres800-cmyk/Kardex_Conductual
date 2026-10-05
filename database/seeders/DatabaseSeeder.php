<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
    'nombre' => 'Test',
    'apellidos' => 'User',
    'email' => 'test@example.com',
]);

        $this->call([
            ConfiguracionSeeder::class,
        ]);
    }
}