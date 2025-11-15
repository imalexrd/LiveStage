<x-app-layout>
    <div class="bg-gray-100">
        <!-- Profile Header -->
        <div class="relative bg-white">
            <!-- Banner Image -->
            <div class="h-48 md:h-64 bg-cover bg-center" style="background-image: url('{{ $musician->banner_image_path ? asset('storage/' . $musician->banner_image_path) : 'https://via.placeholder.com/1500x500' }}');">
            </div>

            <!-- Profile Picture and Artist Name -->
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="-mt-12 sm:-mt-16 sm:flex sm:items-end sm:space-x-5">
                    <div class="flex">
                        <img class="h-24 w-24 rounded-full ring-4 ring-white sm:h-32 sm:w-32 object-cover"
                             src="{{ $musician->manager->profile_picture_url ? asset('storage/' . $musician->manager->profile_picture_url) : 'https://via.placeholder.com/150' }}"
                             alt="{{ $musician->artist_name }}">
                    </div>
                    <div class="mt-6 sm:flex-1 sm:min-w-0 sm:flex sm:items-center sm:justify-end sm:space-x-6 sm:pb-1">
                        <div class="sm:hidden md:block mt-6 min-w-0 flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 truncate">{{ $musician->artist_name }}</h1>
                            <p class="text-sm text-gray-500">{{ $musician->location_city }}, {{ $musician->location_state }}</p>
                        </div>
                    </div>
                </div>
                <div class="sm:hidden md:block">
                    <div class="h-16"></div>
                </div>
            </div>
        </div>

        <div class="py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left Column (Main Content) -->
                <div class="md:col-span-2 space-y-8">
                    <!-- About Section -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold text-gray-900 border-b pb-2">About {{ $musician->artist_name }}</h3>
                        <p class="mt-4 text-gray-700 whitespace-pre-line">{{ $musician->bio }}</p>
                    </div>

                    <!-- Tags Section -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold text-gray-900 border-b pb-2">Specialties</h3>
                        <div class="mt-4">
                            <h4 class="font-semibold text-gray-800">Genres</h4>
                            <div class="flex flex-wrap mt-2">
                                @forelse ($musician->genres as $genre)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold mr-2 mb-2 px-3 py-1 rounded-full">{{ $genre->name }}</span>
                                @empty
                                    <p class="text-gray-500">No genres specified.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold text-gray-800">Event Types</h4>
                            <div class="flex flex-wrap mt-2">
                                @forelse ($musician->eventTypes as $eventType)
                                    <span class="inline-block bg-green-100 text-green-800 text-sm font-semibold mr-2 mb-2 px-3 py-1 rounded-full">{{ $eventType->name }}</span>
                                @empty
                                     <p class="text-gray-500">No event types specified.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Media Section -->
                    <div class="bg-white p-6 rounded-lg shadow">
                         <h3 class="text-xl font-semibold text-gray-900 border-b pb-2">Media Gallery</h3>
                         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                            @forelse ($musician->media as $media)
                                <div class="group relative">
                                    @if ($media->type === 'image')
                                        <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $musician->artist_name }}" class="w-full h-40 object-cover rounded-md">
                                    @elseif ($media->type === 'video')
                                        <video src="{{ asset('storage/' . $media->path) }}" controls class="w-full h-40 object-cover rounded-md"></video>
                                    @elseif ($media->type === 'audio')
                                        <div class="bg-gray-800 p-4 rounded-md h-40 flex flex-col justify-center">
                                            <audio src="{{ asset('storage/' . $media->path) }}" controls class="w-full"></audio>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 col-span-full">No media has been uploaded yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Column (Booking) -->
                <div class="md:col-span-1">
                    <div class="sticky top-10 bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold text-gray-900">Book this Musician</h3>
                        <p class="text-sm text-gray-600 mt-2">Starting from <span class="font-bold text-lg text-gray-800">${{ $musician->base_price_per_hour }}</span>/hour</p>
                        <div class="mt-6">
                             @livewire('booking-request-form', ['musicianProfile' => $musician])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
