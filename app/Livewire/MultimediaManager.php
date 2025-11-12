<?php

namespace App\Livewire;

use App\Models\MusicianProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class MultimediaManager extends Component
{
    use WithFileUploads;

    public MusicianProfile $profile;
    public $bannerImage;
    public $galleryImages = [];
    public $videoFile;
    public $audioFile;

    public function mount()
    {
        $this->profile = Auth::user()->musicianProfile()->firstOrFail();
    }

    public function saveBannerImage()
    {
        $this->validate([
            'bannerImage' => 'image|max:10240', // 10MB Max
        ]);

        $path = $this->bannerImage->store('banners');

        $this->profile->update(['banner_image_path' => $path]);

        $this->bannerImage = null;

        $this->profile->refresh();

        session()->flash('message', 'Banner image uploaded successfully.');
    }

    public function saveGalleryImages()
    {
        $this->validate([
            'galleryImages.*' => 'image|max:10240', // 10MB Max
        ]);

        foreach ($this->galleryImages as $image) {
            $path = $image->store('gallery');
            $this->profile->media()->create([
                'file_path' => $path,
                'type' => 'image',
            ]);
        }

        $this->galleryImages = [];

        $this->profile->refresh();

        session()->flash('message', 'Gallery images uploaded successfully.');
    }

    public function saveVideo()
    {
        $this->validate([
            'videoFile' => 'mimes:mp4,mov,ogg,qt|max:51200', // 50MB Max
        ]);

        $path = $this->videoFile->store('videos');

        $this->profile->media()->create([
            'file_path' => $path,
            'type' => 'video',
        ]);

        $this->videoFile = null;

        $this->profile->refresh();

        session()->flash('message', 'Video uploaded successfully.');
    }

    public function saveAudio()
    {
        $this->validate([
            'audioFile' => 'mimes:mp3,wav|max:20480', // 20MB Max
        ]);

        $path = $this->audioFile->store('audio');

        $this->profile->media()->create([
            'file_path' => $path,
            'type' => 'audio',
        ]);

        $this->audioFile = null;

        $this->profile->refresh();

        session()->flash('message', 'Audio uploaded successfully.');
    }

    public function deleteMedia($mediaId)
    {
        $media = $this->profile->media()->findOrFail($mediaId);
        Storage::delete($media->file_path);
        $media->delete();
        $this->profile->refresh();
        session()->flash('message', 'Media deleted successfully.');
    }


    public function render()
    {
        return view('livewire.multimedia-manager', [
            'media' => $this->profile->media,
        ]);
    }
}
