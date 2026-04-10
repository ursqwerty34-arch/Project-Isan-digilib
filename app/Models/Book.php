<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['kode_buku', 'title', 'author', 'publisher', 'year', 'isbn', 'stock', 'category', 'category_id', 'synopsis', 'cover'];

    public function loans() { return $this->hasMany(Loan::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function reviews() { return $this->hasMany(BookReview::class); }
    public function favorites() { return $this->hasMany(BookFavorite::class); }

    public function avgRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}
