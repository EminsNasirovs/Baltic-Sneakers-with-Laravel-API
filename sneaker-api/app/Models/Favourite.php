<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $fillable = [
        'user_id',
        'favouritable_id',
        'favouritable_type',
    ];

    // Polymorphic relationship to any "favouritable" model
    public function favouritable()
    {
        return $this->morphTo();
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
