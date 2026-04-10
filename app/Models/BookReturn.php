<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReturn extends Model
{
    protected $table    = 'returns';
    protected $fillable = ['loan_id', 'confirmed_by', 'return_date', 'fine', 'fine_status'];

    public function loan()        { return $this->belongsTo(Loan::class); }
    public function confirmedBy() { return $this->belongsTo(User::class, 'confirmed_by'); }
}
