<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $musician->artist_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                @if ($musician->banner_image_path)
                    <img src="{{ asset('storage/' . $musician->banner_image_path) }}" alt="Banner Image" class="w-full h-auto">
                @endif
                <div class="p-6 bg-white border-b border-gray-200">
                    <p><strong>Bio:</strong> {{ $musician->bio }}</p>
                    <p><strong>Location:</strong> {{ $musician->location_city }}, {{ $musician->location_state }}</p>
                    <p><strong>Base Price:</strong> ${{ $musician->base_price_per_hour }}/hour</p>
                </div>

                <!-- Gallery -->
                @if ($musician->media->where('type', 'image')->count() > 0)
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-2xl font-bold mb-4">Gallery</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($musician->media->where('type', 'image') as $image)
                                <img src="{{ asset('storage/' . $image->file_path) }}" alt="Gallery Image" class="w-full h-auto rounded-lg shadow-md">
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Video -->
                @if ($musician->media->where('type', 'video')->count() > 0)
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-2xl font-bold mb-4">Video</h3>
                        @foreach($musician->media->where('type', 'video') as $video)
                            <video controls class="w-full h-auto rounded-lg shadow-md">
                                <source src="{{ asset('storage/' . $video->file_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endforeach
                    </div>
                @endif

                <!-- Audio -->
                @if ($musician->media->where('type', 'audio')->count() > 0)
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-2xl font-bold mb-4">Audio</h3>
                        @foreach($musician->media->where('type', 'audio') as $audio)
                            <audio controls class="w-full">
                                <source src="{{ asset('storage/' . $audio->file_path) }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
