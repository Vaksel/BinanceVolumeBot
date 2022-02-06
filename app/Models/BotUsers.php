<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotUsers extends Model
{
    use HasFactory;

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }

    public function botman()
    {
        return $this->belongsTo('App\Models\Botman');
    }
}
