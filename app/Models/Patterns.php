<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patterns extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dateFormat = 'U';

    public $timestamps = false;
}
