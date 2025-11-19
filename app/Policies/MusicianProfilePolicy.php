<?php

namespace App\Policies;

use App\Models\MusicianProfile;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MusicianProfilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, MusicianProfile $musicianProfile): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'manager' && $user->musicianProfile === null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MusicianProfile $musicianProfile): bool
    {
        return $user->id === $musicianProfile->manager_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MusicianProfile $musicianProfile): bool
    {
        return $user->id === $musicianProfile->manager_id;
    }
}
