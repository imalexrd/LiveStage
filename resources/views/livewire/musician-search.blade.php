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
                        <select wire:model.live="location" id="location" class="mt-1 block w-full">
                            <option value="">All Cities</option>
                            @foreach($supportedCities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4">
                        <label for="genres" class="block text-sm font-medium text-gray-700">Genres</label>
                        <div class="mt-2 space-y-2">
                            @foreach($genres as $genre)
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedGenres" value="{{ $genre->id }}" class="form-checkbox">
                                        <span class="ml-2">{{ $genre->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model.live="genreMatch" value="any" class="form-radio">
                                <span class="ml-2">Match Any</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="radio" wire:model.live="genreMatch" value="all" class="form-radio">
                                <span class="ml-2">Match All</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="event_types" class="block text-sm font-medium text-gray-700">Event Types</label>
                        <div class="mt-2 space-y-2">
                            @foreach($eventTypes as $eventType)
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedEventTypes" value="{{ $eventType->id }}" class="form-checkbox">
                                        <span class="ml-2">{{ $eventType->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model.live="eventTypeMatch" value="any" class="form-radio">
                                <span class="ml-2">Match Any</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="radio" wire:model.live="eventTypeMatch" value="all" class="form-radio">
                                <span class="ml-2">Match All</span>
                            </label>
                        </div>
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
