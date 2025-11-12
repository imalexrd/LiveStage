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
                    <p><strong>Bio:</strong> {{ $musician->bio }}</p>
                    <p><strong>Location:</strong> {{ $musician->location_city }}, {{ $musician->location_state }}</p>
                    <p><strong>Base Price:</strong> ${{ $musician->base_price_per_hour }}/hour</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
