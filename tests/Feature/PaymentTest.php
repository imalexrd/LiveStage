<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MusicianProfile;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Stripe\PaymentIntent;
use Mockery;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_client_can_pay_for_a_booking()
    {
        $client = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'manager']);
        $musician = MusicianProfile::factory()->for($manager, 'manager')->create([
            'stripe_connect_id' => 'acct_1234567890',
        ]);
        $booking = Booking::factory()->for($client, 'client')->for($musician, 'musicianProfile')->create([
            'status' => 'accepted',
            'total_price' => 100,
        ]);

        $this->actingAs($client);

        // Set a dummy API key to prevent "No API key" error
        config(['services.stripe.secret' => 'sk_test_dummy']);

        // Mock the static create method on the PaymentIntent class
        Mockery::mock('alias:'.PaymentIntent::class)
            ->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'pi_123',
                'client_secret' => 'pi_123_secret_456',
            ]);

        $response = $this->get(route('payments.create', $booking));

        $response->assertStatus(200);
        $response->assertViewIs('payments.checkout');
        $response->assertViewHas('clientSecret', 'pi_123_secret_456');
        $response->assertViewHas('booking');
    }
}
