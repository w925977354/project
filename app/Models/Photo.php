<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    /**
     * The attributes that are mass assignable.
     * These fields can be bulk-assigned when creating/updating photos.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_path',
    ];

    /**
     * Define the relationship: A photo belongs to a user.
     * This allows accessing the photo's owner via $photo->user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
