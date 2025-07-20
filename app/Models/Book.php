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
    ];

    protected $casts = [
        'rating' => 'float',
        'description' => 'string',
    ];

    protected $attributes = [
        'rating' => 0,
    ];
}
