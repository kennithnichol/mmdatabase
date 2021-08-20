<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSignature extends Model
{
    public function sections()
    {
        return $this->belongsToMany('App\Models\Section');
    }
}
