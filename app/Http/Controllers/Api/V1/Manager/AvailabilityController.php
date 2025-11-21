<?php

namespace App\Http\Controllers\Api\V1\Manager;

use App\Http\Controllers\Controller;
use App\Data\StoreAvailabilityData;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AvailabilityController extends Controller
{
    public function __construct(protected AvailabilityService $availabilityService)
    {}

    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'manager' || !$user->musicianProfile) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        $availabilities = $this->availabilityService->getFutureAvailabilities($user->musicianProfile);

        return response()->json($availabilities);
    }

    public function store(StoreAvailabilityData $data)
    {
        $user = Auth::user();
        if ($user->role !== 'manager' || !$user->musicianProfile) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $availability = $this->availabilityService->blockDate(
                $user->musicianProfile,
                $data->unavailable_date,
                $data->reason
            );
            return response()->json($availability, 201);
        } catch (ValidationException $e) {
            // The prompt's original controller returned 422 with a custom message.
            // ValidationException usually returns 422 automatically, but let's match the format if needed.
            // Ideally we let Laravel handle it, but previous code did explicit check.
            return response()->json(['error' => $e->validator->errors()->first()], 422);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'manager' || !$user->musicianProfile) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $this->availabilityService->unblockDate($user->musicianProfile, $id);
            return response()->json(['message' => 'Availability deleted successfully.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Availability not found.'], 404);
        }
    }
}
