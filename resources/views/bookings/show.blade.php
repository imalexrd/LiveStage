<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Booking Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

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
                    <p><strong>Event Location:</strong> {{ $booking->location_address }}</p>
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
                    @endif

                    @if (auth()->user()->role === 'client' && $booking->status === 'pending')
                        <div class="mt-4">
                            <a href="{{ route('bookings.pay', $booking) }}" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                Pay Now
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
