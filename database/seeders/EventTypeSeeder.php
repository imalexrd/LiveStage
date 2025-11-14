<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EventType;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventTypes = [
            ['name' => 'Weddings'],
            ['name' => 'Corporate Events'],
            ['name' => 'Private Parties'],
            ['name' => 'Concerts'],
            ['name' => 'Festivals'],
            ['name' => 'Lounge DJs'],
            ['name' => 'Pianists'],
            ['name' => 'Rock Bands'],
        ];

        foreach ($eventTypes as $eventType) {
            EventType::create($eventType);
        }
    }
}
