<div>
    <form wire:submit.prevent="save">
        <div>
            <x-input-label for="artist_name" :value="__('Artist Name')" />
            <x-text-input wire:model="artist_name" id="artist_name" class="block mt-1 w-full" type="text" name="artist_name" required />
            @error('artist_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea wire:model="bio" id="bio" class="block mt-1 w-full"></textarea>
            @error('bio') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="location_city" :value="__('City')" />
            <select wire:model="location_city" id="location_city" class="block mt-1 w-full">
                <option value="">Select a city</option>
                @foreach($supportedCities as $city)
                    <option value="{{ $city }}">{{ $city }}</option>
                @endforeach
            </select>
            @error('location_city') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="location_state" :value="__('State')" />
            <x-text-input wire:model="location_state" id="location_state" class="block mt-1 w-full" type="text" name="location_state" required />
            @error('location_state') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="base_location" :value="__('Base Location')" />
            <x-secondary-button type="button" class="mt-1" wire:click="openLocationModal">
                {{ __('Set Base Location on Map') }}
            </x-secondary-button>
            <div class="mt-2 text-sm text-gray-600">
                Lat: <span class="font-medium">{{ $latitude ?? 'Not set' }}</span>,
                Lng: <span class="font-medium">{{ $longitude ?? 'Not set' }}</span>
            </div>
            @error('latitude') <span class="text-danger">{{ $message }}</span> @enderror
            @error('longitude') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="base_price_per_hour" :value="__('Base Price Per Hour ($)')" />
            <x-text-input wire:model="base_price_per_hour" id="base_price_per_hour" class="block mt-1 w-full" type="number" name="base_price_per_hour" required step="0.01" />
            @error('base_price_per_hour') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="travel_radius_miles" :value="__('Included Travel Radius (Miles)')" />
            <x-text-input wire:model="travel_radius_miles" id="travel_radius_miles" class="block mt-1 w-full" type="number" name="travel_radius_miles" step="1" />
            @error('travel_radius_miles') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="max_travel_distance_miles" :value="__('Max Travel Distance (Miles)')" />
            <x-text-input wire:model="max_travel_distance_miles" id="max_travel_distance_miles" class="block mt-1 w-full" type="number" name="max_travel_distance_miles" step="1" />
            @error('max_travel_distance_miles') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="price_per_extra_mile" :value="__('Price Per Extra Mile ($)')" />
            <x-text-input wire:model="price_per_extra_mile" id="price_per_extra_mile" class="block mt-1 w-full" type="number" name="price_per_extra_mile" step="0.01" />
            @error('price_per_extra_mile') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label :value="__('Genres')" />
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($genres as $genre)
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="selectedGenres" value="{{ $genre->id }}" class="form-checkbox">
                        <span class="ml-2">{{ $genre->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mt-4">
            <x-input-label :value="__('Event Types')" />
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($eventTypes as $eventType)
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="selectedEventTypes" value="{{ $eventType->id }}" class="form-checkbox">
                        <span class="ml-2">{{ $eventType->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </form>
</div>
