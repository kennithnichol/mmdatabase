<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    use HasFactory;

    public function composer()
    {
        return $this->hasOne('App\Models\Composer');
    }

    public function editions()
    {
        return $this->hasMany('App\Models\Edition');
    }

    public function movements()
    {
        return $this->hasMany('App\Models\Movement');
    }
}
