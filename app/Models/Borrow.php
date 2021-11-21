<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $table = 'user_books';

    protected $fillable = [
        'id', 'book_id', 'user_id', 'expired_at', 'status'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function book() {
        return $this->belongsTo(Book::class);
    }

    public function statusObj() {
        return $this->belongsTo(Status::class, 'status');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
