<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status_catalog';

    const REQUESTED = 1;
    const BORRORED = 2;
    const NOT_BORROWED = 3;
    const RETURNED = 4;
    const EXPIRED = 5;
}
