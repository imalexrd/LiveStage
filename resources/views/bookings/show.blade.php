<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Booking Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p><strong>
                        @if (auth()->user()->role === 'manager')
                            Client:
                        @else
                            Musician:
                        @endif
                    </strong>
                        @if (auth()->user()->role === 'manager')
                            {{ $booking->client->name }}
                        @else
                            {{ $booking->musicianProfile->artist_name }}
                        @endif
                    </p>
                    <p><strong>Event Date:</strong> {{ $booking->event_date }}</p>
                    <p><strong>Event Location:</strong> {{ $booking->event_location }}</p>
                    <p><strong>Event Details:</strong> {{ $booking->event_details }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>

                    @if (auth()->user()->role === 'manager' && $booking->status === 'pending')
                        <div class="mt-4">
                            <form action="{{ route('bookings.approve', $booking) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700">Approve</button>
                            </form>
                            <form action="{{ route('bookings.reject', $booking) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">Reject</button>
                            </form>
                        </div>
                    @elseif (auth()->user()->role === 'client' && $booking->status === 'accepted')
                        <div class="mt-4">
                            <a href="{{ route('payments.create', $booking) }}" class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Pay Now</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
