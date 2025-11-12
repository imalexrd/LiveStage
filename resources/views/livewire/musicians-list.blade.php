<div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
        @foreach ($musicians as $musician)
            <div class="p-4 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-bold">{{ $musician->artist_name }}</h3>
                <p class="text-gray-600">{{ $musician->location_city }}, {{ $musician->location_state }}</p>
                <a href="{{ route('musician.profile.show', $musician->uuid) }}" class="text-blue-500 hover:underline">View Profile</a>
            </div>
        @endforeach
    </div>
</div>
