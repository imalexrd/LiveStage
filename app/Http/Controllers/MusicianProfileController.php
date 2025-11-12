<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MusicianProfile;

class MusicianProfileController extends Controller
{
    public function show($uuid)
    {
        $musician = MusicianProfile::where('uuid', $uuid)->where('is_approved', true)->firstOrFail();

        return view('musician-profile-show', [
            'musician' => $musician,
        ]);
    }
}
