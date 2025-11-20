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
            <x-input-label for="base_location" :value="__('Base Location')" />
            <x-secondary-button type="button" class="mt-1" wire:click="openLocationModal">
                {{ $location_address ?? __('Set Base Location on Map') }}
            </x-secondary-button>
            @if($location_address)
            <div class="mt-2 text-sm text-gray-500">
                Your current base of operations is set to: <span class="font-semibold text-gray-700">{{ $location_address }}</span>
            </div>
            @endif
            @error('location_address') <span class="text-danger">{{ $message }}</span> @enderror
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
            <x-input-label for="minimum_booking_notice_days" :value="__('Minimum Booking Notice (Days)')" />
            <x-text-input wire:model="minimum_booking_notice_days" id="minimum_booking_notice_days" class="block mt-1 w-full" type="number" name="minimum_booking_notice_days" step="1" />
            @error('minimum_booking_notice_days') <span class="text-danger">{{ $message }}</span> @enderror
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
