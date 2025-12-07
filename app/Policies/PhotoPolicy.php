<?php

namespace App\Policies;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PhotoPolicy
{
    /**
     * Determine whether anyone (including guests) can view all photos.
     * No authentication required for viewing the gallery homepage.
     */
    public function viewAny(?User $user): bool
    {
        // Everyone can view the photo gallery (public access)
        return true;
    }

    /**
     * Determine whether the user can view a specific photo.
     * Individual photos are publicly viewable.
     */
    public function view(?User $user, Photo $photo): bool
    {
        // Everyone can view individual photos (public access)
        return true;
    }

    /**
     * Determine whether the user can create/upload photos.
     * Only authenticated users can upload photos.
     */
    public function create(User $user): bool
    {
        // Only logged-in users can upload photos
        return $user !== null;
    }

    /**
     * Determine whether the user can update the photo.
     * Only the photo owner can update their own photos.
     */
    public function update(User $user, Photo $photo): bool
    {
        // Users can only update their own photos
        return $user->id === $photo->user_id;
    }

    /**
     * Determine whether the user can delete the photo.
     * Regular users can delete their own photos.
     * Administrators (is_admin=true) can delete ANY photo (content moderation).
     */
    public function delete(User $user, Photo $photo): bool
    {
        // Admin can delete any photo for content moderation
        if ($user->is_admin) {
            return true;
        }

        // Regular users can only delete their own photos
        return $user->id === $photo->user_id;
    }

    /**
     * Determine whether the user can restore the photo.
     * Same permission as deletion.
     */
    public function restore(User $user, Photo $photo): bool
    {
        return $this->delete($user, $photo);
    }

    /**
     * Determine whether the user can permanently delete the photo.
     * Same permission as deletion.
     */
    public function forceDelete(User $user, Photo $photo): bool
    {
        return $this->delete($user, $photo);
    }
}
