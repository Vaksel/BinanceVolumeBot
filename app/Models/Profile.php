<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    /**
     * Получить пары профиля.
     */
    public function pairs()
    {
        return $this->hasMany('App\Models\SavedPairs');
    }

    public function botUsers()
    {
        return $this->hasMany('App\Models\BotUsers');
    }


}
