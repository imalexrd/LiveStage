<div x-data="{ filtersOpen: false }">
    <!-- Page Content -->
    <div class="relative">
        <!-- Mobile Filter Button -->
        <div class="md:hidden fixed bottom-4 right-4 z-20">
            <button @click="filtersOpen = true" class="bg-blue-600 text-white rounded-full p-4 shadow-lg focus:outline-none" aria-label="Open filters">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L16 11.414V16a1 1 0 01-.293.707l-2 2A1 1 0 0113 18v-1.586l-1.707-1.707A1 1 0 0111 14V4a1 1 0 011-1h2a1 1 0 011 1v10.586l2.293 2.293A1 1 0 0118 18v-2.414l-4.707-4.707A1 1 0 0113 10V4z"></path></svg>
            </button>
        </div>

        <!-- Main Content -->
        <div class="flex">
            <!-- Desktop Filters Sidebar -->
            <div class="hidden md:block w-1/4 bg-white p-6 rounded-lg shadow-lg">
                <!-- Filters Content (Duplicate for Desktop) -->
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
                        <button type="button" wire:click="openLocationModal" class="w-full bg-white border border-gray-300 rounded-lg shadow-sm py-2 px-4 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ $selectedAddress ?? 'Select Location' }}
                        </button>
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
                @if($searchExpanded)
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                        <p>No musicians found in your immediate area. Showing the next closest results.</p>
                    </div>
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                    @forelse($musicians as $musician)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 ease-in-out">
                            <a href="{{ route('musician.profile.show', ['uuid' => $musician->uuid]) }}">
                                <img src="{{ $musician->banner_image_path ? asset('storage/' . $musician->banner_image_path) : 'https://via.placeholder.com/300x200' }}" alt="{{ $musician->artist_name }}" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ $musician->artist_name }}</h4>
                                    @if($musician->distance)
                                        <p class="text-gray-600 text-sm mb-4">{{ round($musician->distance, 2) }} miles away</p>
                                    @else
                                        <p class="text-gray-600 text-sm mb-4">{{ $musician->location_city }}, {{ $musician->location_state }}</p>
                                    @endif
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

        <!-- Mobile Filters Modal -->
        <div x-show="filtersOpen" class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50 md:hidden" @click.self="filtersOpen = false">
            <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-lg mx-auto p-6 max-h-[85vh] overflow-y-auto">
                <!-- Filters Content (Duplicate for Mobile) -->
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
                        <button type="button" wire:click="openLocationModal" class="w-full bg-white border border-gray-300 rounded-lg shadow-sm py-2 px-4 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ $selectedAddress ?? 'Select Location' }}
                        </button>
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

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" @click="filtersOpen = false" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition duration-300">Close</button>
                        <button type="button" wire:click="clearFilters" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <livewire:location-picker-modal />
</div>
