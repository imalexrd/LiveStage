<div>
    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date</label>
            <input wire:model="event_date" type="date" id="event_date" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            @error('event_date') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="event_location" class="block text-sm font-medium text-gray-700">Event Location</label>
            <button type="button" wire:click="openLocationModal" class="w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $location_address ?? 'Select Location' }}
            </button>
            @error('location_address') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="event_details" class="block text-sm font-medium text-gray-700">Event Details</label>
            <textarea wire:model="event_details" id="event_details" rows="4" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
            @error('event_details') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="p-4 mt-4 bg-gray-100 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900">Estimated Price</h3>
            <div class="mt-2 text-sm text-gray-600">
                <div class="flex justify-between mt-2 pt-2 border-t border-gray-300 font-bold">
                    <span>Total:</span>
                    <span>${{ number_format($this->totalPrice, 2) }}</span>
                </div>
            </div>
        </div>

        <button type="submit" class="px-4 py-2 mt-4 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Send Request</button>
    </form>
    <livewire:location-picker-modal />
</div>
