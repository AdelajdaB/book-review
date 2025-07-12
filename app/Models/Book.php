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
    ];

    public function review() {
        return $this->hasMany(Review::class);
    }
}
