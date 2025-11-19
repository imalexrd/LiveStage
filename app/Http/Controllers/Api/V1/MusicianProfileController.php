<?php

namespace App\Http\Controllers\Api\V1;

use App\Data\MusicianSearchFilterData;
use App\Data\MusicianProfileData;
use App\Http\Controllers\Controller;
use App\Http\Resources\MusicianProfileResource;
use App\Models\MusicianProfile;
use App\Services\MusicianProfileService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MusicianProfileController extends Controller
{
    protected $musicianProfileService;

    public function __construct(MusicianProfileService $musicianProfileService)
    {
        $this->musicianProfileService = $musicianProfileService;
    }

    public function index(MusicianSearchFilterData $filters): AnonymousResourceCollection
    {
        $results = $this->musicianProfileService->search($filters);

        return MusicianProfileResource::collection($results['musicians'])
            ->additional(['meta' => ['searchExpanded' => $results['searchExpanded']]]);
    }

    public function show(MusicianProfile $profile): MusicianProfileResource
    {
        return new MusicianProfileResource($profile);
    }

    public function store(MusicianProfileData $data, Request $request): MusicianProfileResource
    {
        $this->authorize('create', MusicianProfile::class);

        $musicianProfile = $this->musicianProfileService->create($data, $request->user());

        return new MusicianProfileResource($musicianProfile);
    }

    public function update(Request $request, MusicianProfile $profile): MusicianProfileResource
    {
        $this->authorize('update', $profile);

        $data = MusicianProfileData::from($request->all());

        $updatedProfile = $this->musicianProfileService->updateProfile(
            $profile->manager,
            $data,
            $data->selectedGenres ?? [],
            $data->selectedEventTypes ?? []
        );

        return new MusicianProfileResource($updatedProfile);
    }

    public function destroy(MusicianProfile $profile): \Illuminate\Http\Response
    {
        $this->authorize('delete', $profile);

        $profile->delete();

        return response()->noContent();
    }
}
