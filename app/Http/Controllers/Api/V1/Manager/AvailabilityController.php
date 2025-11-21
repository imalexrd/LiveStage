<?php

namespace App\Http\Controllers\Api\V1\Manager;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Data\StoreAvailabilityData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        // Ideally use Policy, but check role here for simplicity as per previous pattern
        if ($user->role !== 'manager' || !$user->musicianProfile) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        $availabilities = $user->musicianProfile->availabilities()
            ->where('unavailable_date', '>=', now()->toDateString())
            ->orderBy('unavailable_date')
            ->get();

        return response()->json($availabilities);
    }

    public function store(StoreAvailabilityData $data)
    {
        $user = Auth::user();
        if ($user->role !== 'manager' || !$user->musicianProfile) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        $profile = $user->musicianProfile;

        // Check if already blocked
        if ($profile->availabilities()->where('unavailable_date', $data->unavailable_date)->exists()) {
            return response()->json(['error' => 'Date is already blocked.'], 422);
        }

        $availability = $profile->availabilities()->create([
            'unavailable_date' => $data->unavailable_date,
            'reason' => $data->reason,
        ]);

        return response()->json($availability, 201);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'manager' || !$user->musicianProfile) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        $availability = $user->musicianProfile->availabilities()->find($id);

        if (!$availability) {
            return response()->json(['error' => 'Availability not found.'], 404);
        }

        $availability->delete();

        return response()->json(['message' => 'Availability deleted successfully.']);
    }
}
