<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'description',
        'rating',
        'status',
        'cover_image',
    ];

    protected $casts = [
        'rating' => 'float',
        'description' => 'string',
    ];

    protected $attributes = [
        'rating' => 0,
    ];

    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        return asset('storage/' . $this->cover_image);
    }

    public function getHasCoverAttribute(): bool
    {
        return !empty($this->cover_image);
    }
}
