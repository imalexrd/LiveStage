<div>
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Filters</h3>
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" wire:model.live="search" id="search" class="mt-1 block w-full">
                    </div>
                    <div class="mt-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" wire:model.live="location" id="location" class="mt-1 block w-full">
                        <p class="text-xs text-gray-500 mt-1">Search by city for now.</p>
                    </div>
                    <div class="mt-4">
                        <label for="radius" class="block text-sm font-medium text-gray-700">Radius (km)</label>
                        <input type="number" wire:model.live="radius" id="radius" class="mt-1 block w-full">
                    </div>
                    <div class="mt-4">
                        <label for="genres" class="block text-sm font-medium text-gray-700">Genres</label>
                        <select wire:model.live="selectedGenres" id="genres" class="mt-1 block w-full" multiple>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4">
                        <label for="event_types" class="block text-sm font-medium text-gray-700">Event Types</label>
                        <select wire:model.live="selectedEventTypes" id="event_types" class="mt-1 block w-full" multiple>
                            @foreach($eventTypes as $eventType)
                                <option value="{{ $eventType->id }}">{{ $eventType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4">
                        <label for="price_range" class="block text-sm font-medium text-gray-700">Price Range</label>
                        <div>
                            <span>${{ $minPrice }}</span> - <span>${{ $maxPrice }}</span>
                        </div>
                        <input type="range" wire:model.live="minPrice" min="0" max="500" class="w-full">
                        <input type="range" wire:model.live="maxPrice" min="501" max="1000" class="w-full">
                    </div>
                </div>
            </div>
            <div class="md:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($musicians as $musician)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <a href="{{ route('musician.profile.show', ['uuid' => $musician->uuid]) }}">
                                <img src="{{ asset('storage/' . $musician->banner_image_path) }}" alt="{{ $musician->artist_name }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h4 class="text-lg font-semibold">{{ $musician->artist_name }}</h4>
                                    <p class="text-gray-600">{{ $musician->location_city }}, {{ $musician->location_state }}</p>
                                    <p class="text-gray-800 font-bold mt-2">${{ $musician->base_price_per_hour }}/hr</p>
                                </div>
                            </a>
                        </div>
                    @empty
                        <p>No musicians found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
