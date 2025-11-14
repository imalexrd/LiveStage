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
            <x-input-label for="latitude" :value="__('Latitude')" />
            <x-text-input wire:model="latitude" id="latitude" class="block mt-1 w-full" type="text" name="latitude" />
            @error('latitude') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="longitude" :value="__('Longitude')" />
            <x-text-input wire:model="longitude" id="longitude" class="block mt-1 w-full" type="text" name="longitude" />
            @error('longitude') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="base_price_per_hour" :value="__('Base Price Per Hour')" />
            <x-text-input wire:model="base_price_per_hour" id="base_price_per_hour" class="block mt-1 w-full" type="number" name="base_price_per_hour" required />
            @error('base_price_per_hour') <span class="text-danger">{{ $message }}</span> @enderror
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
