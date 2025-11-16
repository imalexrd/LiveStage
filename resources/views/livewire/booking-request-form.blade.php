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
            <label for="address" class="block text-sm font-medium text-gray-700">Event Location (Optional)</label>
            <x-google-places-autocomplete />
            @error('location_address') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="event_details" class="block text-sm font-medium text-gray-700">Event Details</label>
            <textarea wire:model="event_details" id="event_details" rows="4" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
            @error('event_details') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Send Request</button>
    </form>
</div>
