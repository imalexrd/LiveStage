<?php

namespace App\Http\Controllers\Api\V1;

use App\Data\MusicianSearchFilterData;
use App\Http\Controllers\Controller;
use App\Http\Resources\MusicianProfileResource;
use App\Services\MusicianProfileService;
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
}
