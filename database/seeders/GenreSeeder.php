<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Rock'],
            ['name' => 'Pop'],
            ['name' => 'Jazz'],
            ['name' => 'Classical'],
            ['name' => 'Hip Hop'],
            ['name' => 'Electronic'],
            ['name' => 'Country'],
            ['name' => 'R&B'],
            ['name' => 'Reggae'],
            ['name' => 'Latin'],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
