<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $musician->artist_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold">About</h3>
                            <p class="mt-2 text-gray-600">{{ $musician->bio }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Details</h3>
                            <p class="mt-2 text-gray-600"><strong>Location:</strong> {{ $musician->location_city }}, {{ $musician->location_state }}</p>
                            <p class="mt-2 text-gray-600"><strong>Base Rate:</strong> ${{ $musician->base_price_per_hour }}/hour</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Genres</h3>
                    <div class="flex flex-wrap mt-2">
                        @foreach ($musician->genres as $genre)
                            <span class="inline-block px-2 py-1 mr-2 mb-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-full">{{ $genre->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Event Types</h3>
                    <div class="flex flex-wrap mt-2">
                        @foreach ($musician->eventTypes as $eventType)
                            <span class="inline-block px-2 py-1 mr-2 mb-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-full">{{ $eventType->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Media</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-2">
                        @foreach ($musician->media as $media)
                            <div class="overflow-hidden rounded-lg shadow-md">
                                @if ($media->type === 'image')
                                    <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $musician->artist_name }}" class="object-cover w-full h-48">
                                @elseif ($media->type === 'video')
                                    <video src="{{ asset('storage/' . $media->path) }}" controls class="object-cover w-full h-48"></video>
                                @elseif ($media->type === 'audio')
                                    <audio src="{{ asset('storage/' . $media->path) }}" controls class="w-full"></audio>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Book this musician</h3>
                    @livewire('booking-request-form', ['musicianProfile' => $musician])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
