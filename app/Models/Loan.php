<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = ['user_id', 'book_id', 'loan_date', 'due_date', 'status', 'pengajuan_status', 'confirmed_by', 'rejection_reason', 'return_requested'];

    public function user()       { return $this->belongsTo(User::class); }
    public function book()       { return $this->belongsTo(Book::class); }
    public function bookReturn() { return $this->hasOne(BookReturn::class, 'loan_id'); }
    public function confirmedBy(){ return $this->belongsTo(User::class, 'confirmed_by'); }
}
