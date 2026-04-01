<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['kode_buku', 'title', 'author', 'publisher', 'year', 'isbn', 'stock', 'category', 'cover'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
