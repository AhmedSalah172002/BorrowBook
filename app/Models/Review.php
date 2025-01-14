<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;


    protected $fillable = [
        'book_id', 'user_id', 'rating', 'comment',
    ];

    /**
     * Get the book that owns the review.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the user that wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
