<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $table = 'user_books';

    public function book() {
        return $this->belongsTo(Book::class);
    }

    public function status() {
        return $this->belongsTo(Status::class, 'status');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
