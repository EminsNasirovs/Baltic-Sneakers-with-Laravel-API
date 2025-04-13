<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 36952bac57d9633e4d650b6ed72d55770f964fd8
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
<<<<<<< HEAD
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
=======
    use HasFactory;

    protected $fillable = ['user_id', 'sneaker_id'];
}
>>>>>>> 36952bac57d9633e4d650b6ed72d55770f964fd8
