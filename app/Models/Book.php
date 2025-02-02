<?php

namespace App\Models;

use App\Collections\BookCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('borrowed_at', 'due_date');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getTitleGenreAttribute()
    {
        return "{$this->title}  {$this->genre->name}";

    }

    public function newCollection(array $models = [])
    {
        return new BookCollection($models);
    }
}
