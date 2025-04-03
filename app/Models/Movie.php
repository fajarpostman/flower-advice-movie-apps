<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    // Start your code here, before you start don't forget to pray
    protected $fillable = ['title', 'year', 'poster', 'imdb_id'];

    public function favoriteMovies()
    {
        return $this->hasMany(FavoriteMovie::class);
    }

}
