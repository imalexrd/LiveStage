<?php

namespace App\Livewire;

use App\Models\MusicianProfile;
use App\Services\BookingService;
use App\Services\StripePaymentService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\On;

class BookingRequestForm extends Component
{
    public MusicianProfile $musicianProfile;

    public $event_date;
    public $location_address;
    public $location_latitude;
    public $location_longitude;
    public $event_details;
    public $basePrice = 0;
    public $weekendSurcharge = 0;
    public $travelFee = 0;
    public $urgencyFee = 0;
    public $appFee = 0;
    public $totalPrice = 0;

    public function mount()
    {
        $this->basePrice = $this->musicianProfile->base_price_per_hour;
        $this->totalPrice = $this->musicianProfile->base_price_per_hour;
    }

    #[On('locationSelected')]
    public function locationSelected($location)
    {
        $this->location_address = $location['address'];
        $this->location_latitude = $location['latitude'];
        $this->location_longitude = $location['longitude'];

        // Reset previous errors
        $this->resetErrorBag('location_address');

        // Note: The final distance check and price calculation
        // will now happen in the BookingService on submit.
        // We can optionally provide a real-time estimate here if needed,
        // but for now, we'll just update the address.
        $this->calculatePrice();
    }

    public function submit(BookingService $bookingService, StripePaymentService $stripePaymentService)
    {
        $data = [
            'event_date' => $this->event_date,
            'location_address' => $this->location_address,
            'location_latitude' => $this->location_latitude,
            'location_longitude' => $this->location_longitude,
            'event_details' => $this->event_details,
        ];

        try {
            $booking = $bookingService->createBooking(auth()->user(), $this->musicianProfile, $data);

            $session = $stripePaymentService->createCheckoutSession($booking);

            return redirect($session->url);

        } catch (ValidationException $e) {
            $this->addError('form', $e->getMessage());
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }
        } catch (\Exception $e) {
            $this->addError('form', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function openLocationModal()
    {
        $this->dispatch('openLocationPicker');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['event_date', 'location_latitude', 'location_longitude'])) {
            $this->calculatePrice();
        }
    }

    public function calculatePrice()
    {
        if ($this->event_date && $this->location_latitude && $this->location_longitude) {
            $bookingService = app(BookingService::class);
            $priceBreakdown = $bookingService->calculateTotalPrice($this->musicianProfile, [
                'event_date' => $this->event_date,
                'location_latitude' => $this->location_latitude,
                'location_longitude' => $this->location_longitude,
            ]);

            $this->basePrice = $priceBreakdown['basePrice'];
            $this->weekendSurcharge = $priceBreakdown['weekendSurcharge'];
            $this->travelFee = $priceBreakdown['travelFee'];
            $this->urgencyFee = $priceBreakdown['urgencyFee'];
            $this->appFee = $priceBreakdown['appFee'];
            $this->totalPrice = $priceBreakdown['totalPrice'];
        }
    }

    public function render()
    {
        return view('livewire.booking-request-form');
    }
}
