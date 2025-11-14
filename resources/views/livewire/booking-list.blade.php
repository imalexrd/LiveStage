<div>
    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                    @if (auth()->user()->role === 'manager')
                        Client
                    @else
                        Musician
                    @endif
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                    Event Date
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                    Status
                </th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Actions</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($bookings as $booking)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            @if (auth()->user()->role === 'manager')
                                {{ $booking->client->name }}
                            @else
                                {{ $booking->musicianProfile->artist_name }}
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $booking->event_date }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 text-xs font-semibold leading-5
                            @switch($booking->status)
                                @case('pending')
                                    bg-yellow-100 text-yellow-800
                                    @break
                                @case('accepted')
                                    bg-green-100 text-green-800
                                    @break
                                @case('rejected')
                                    bg-red-100 text-red-800
                                    @break
                                @case('confirmed')
                                    bg-blue-100 text-blue-800
                                    @break
                                @case('cancelled')
                                    bg-gray-100 text-gray-800
                                    @break
                            @endswitch
                            rounded-full">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                        <a href="{{ route('bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        @if (auth()->user()->role === 'manager' && $booking->status === 'pending')
                            <button wire:click="approveBooking({{ $booking->id }})" class="ml-4 text-green-600 hover:text-green-900">Approve</button>
                            <button wire:click="rejectBooking({{ $booking->id }})" class="ml-4 text-red-600 hover:text-red-900">Reject</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>
