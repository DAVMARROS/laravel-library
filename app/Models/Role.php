<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const ADMIN = 1;
    const USER = 2;

    protected $fillable = [
        'id', 'name'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }
}
