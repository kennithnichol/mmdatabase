<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editors extends Model
{
    use HasFactory;

    public function pieces()
    {
        return $this->hasMany('App\Models\Piece');
    }
}
