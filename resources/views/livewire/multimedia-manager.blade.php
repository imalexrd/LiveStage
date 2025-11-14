<div>
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="text-2xl font-bold mb-4">Manage Your Multimedia Content</h3>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Banner Image -->
        <div class="mb-6">
            <h4 class="text-xl font-semibold mb-2">Banner Image</h4>
            <form wire:submit.prevent="saveBannerImage">
                <input type="file" wire:model="bannerImage">
                @error('bannerImage') <span class="text-red-500">{{ $message }}</span> @enderror
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
                    Save Banner
                </button>
            </form>
            @if ($profile->banner_image_path)
                <div class="mt-4">
                    <img src="{{ asset('storage/' . $profile->banner_image_path) }}" alt="Banner Image" class="w-full h-auto rounded-lg shadow-md">
                </div>
            @endif
        </div>

        <!-- Gallery Images -->
        <div class="mb-6">
            <h4 class="text-xl font-semibold mb-2">Gallery Images</h4>
            <form wire:submit.prevent="saveGalleryImages">
                <input type="file" wire:model="galleryImages" multiple>
                @error('galleryImages.*') <span class="text-red-500">{{ $message }}</span> @enderror
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
                    Upload Images
                </button>
            </form>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($media->where('type', 'image') as $image)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="Gallery Image" class="w-full h-auto rounded-lg shadow-md">
                        <button wire:click="deleteMedia({{ $image->id }})" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 m-2">
                            &times;
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Video -->
        <div class="mb-6">
            <h4 class="text-xl font-semibold mb-2">Video</h4>
            <form wire:submit.prevent="saveVideo">
                <input type="file" wire:model="videoFile">
                @error('videoFile') <span class="text-red-500">{{ $message }}</span> @enderror
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
                    Upload Video
                </button>
            </form>
            @foreach($media->where('type', 'video') as $video)
                <div class="mt-4 relative">
                    <video controls class="w-full h-auto rounded-lg shadow-md">
                        <source src="{{ asset('storage/' . $video->file_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <button wire:click="deleteMedia({{ $video->id }})" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 m-2">
                        &times;
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Audio -->
        <div>
            <h4 class="text-xl font-semibold mb-2">Audio</h4>
            <form wire:submit.prevent="saveAudio">
                <input type="file" wire:model="audioFile">
                @error('audioFile') <span class="text-red-500">{{ $message }}</span> @enderror
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
                    Upload Audio
                </button>
            </form>
            @foreach($media->where('type', 'audio') as $audio)
                <div class="mt-4 relative">
                    <audio controls class="w-full">
                        <source src="{{ asset('storage/' . $audio->file_path) }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    <button wire:click="deleteMedia({{ $audio->id }})" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 m-2">
                        &times;
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>
