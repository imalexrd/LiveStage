<div x-data="{ open: false }">
    <!-- Filter Toggle (Mobile) -->
    <div class="md:hidden mb-4">
        <button @click="open = !open" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
            Filters
        </button>
    </div>

    <div class="flex">
        <!-- Filters Sidebar -->
        <div :class="{'block': open, 'hidden': !open}" class="md:block w-full md:w-1/4 bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-bold mb-6">Filters</h3>
            <form>
                <!-- Search -->
                <div class="mb-6">
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                    <input type="text" wire:model.live="search" id="search" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Location -->
                <div class="mb-6">
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                    <select wire:model.live="location" id="location" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Cities</option>
                        @foreach($supportedCities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Genres -->
                <div class="mb-6">
                    <h4 class="block text-sm font-semibold text-gray-700 mb-2">Genres</h4>
                    <div class="space-y-2">
                        @foreach($genres as $genre)
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="selectedGenres" value="{{ $genre->id }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700">{{ $genre->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-4 flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="genreMatch" value="any" class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Any</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="genreMatch" value="all" class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">All</span>
                        </label>
                    </div>
                </div>

                <!-- Event Types -->
                <div class="mb-6">
                    <h4 class="block text-sm font-semibold text-gray-700 mb-2">Event Types</h4>
                    <div class="space-y-2">
                        @foreach($eventTypes as $eventType)
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="selectedEventTypes" value="{{ $eventType->id }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700">{{ $eventType->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-4 flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="eventTypeMatch" value="any" class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Any</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="eventTypeMatch" value="all" class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">All</span>
                        </label>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-6">
                    <label for="price_range" class="block text-sm font-semibold text-gray-700 mb-2">Price Range</label>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>${{ $minPrice }}</span>
                        <span>${{ $maxPrice }}</span>
                    </div>
                    <div class="mt-2">
                        <input type="range" wire:model.live="minPrice" min="0" max="500" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                        <input type="range" wire:model.live="maxPrice" min="501" max="1000" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer mt-2">
                    </div>
                </div>

                <!-- Clear Filters -->
                <button type="button" wire:click="clearFilters" class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition duration-300">Clear Filters</button>
            </form>
        </div>

        <!-- Musician Results -->
        <div class="w-full md:w-3/4 md:pl-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                @forelse($musicians as $musician)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 ease-in-out">
                        <a href="{{ route('musician.profile.show', ['uuid' => $musician->uuid]) }}">
                            <img src="{{ $musician->banner_image_path ? asset('storage/' . $musician->banner_image_path) : 'https://via.placeholder.com/300x200' }}" alt="{{ $musician->artist_name }}" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ $musician->artist_name }}</h4>
                                <p class="text-gray-600 text-sm mb-4">{{ $musician->location_city }}, {{ $musician->location_state }}</p>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($musician->genres->take(3) as $genre)
                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                                <p class="text-xl font-semibold text-gray-800">${{ $musician->base_price_per_hour }}<span class="text-sm font-normal text-gray-600">/hr</span></p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-lg text-gray-600">No musicians found matching your criteria. Try adjusting your filters.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
