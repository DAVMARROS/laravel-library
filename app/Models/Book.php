<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id', 'name', 'category_id', 'author_id', 'publicated_at'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function category() {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function author() {
        return $this->belongsTo(Author::class)->withTrashed();
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_books');
    }
}
