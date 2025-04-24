<?php

namespace App\Models;

use Illuminate\Cache\HasCacheLock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feedback extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'rate',
        'comment',
    ];
}
