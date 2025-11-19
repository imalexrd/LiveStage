<?php

namespace Tests\Feature\Api\V1;

use App\Models\MusicianProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MusicianProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_a_paginated_list_of_musician_profiles()
    {
        MusicianProfile::factory()->count(10)->create(['is_approved' => true]);

        $response = $this->getJson('/api/v1/musicians');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'artist_name',
                        'bio',
                        'location_city',
                        'location_state',
                        'latitude',
                        'longitude',
                        'base_price_per_hour',
                    ]
                ],
                'meta' => [
                    'searchExpanded'
                ]
            ]);
    }
}
