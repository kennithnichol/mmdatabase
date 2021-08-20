<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public function timeSignature()
    {
        return $this->hasOne('App\Models\TimeSignature');
    }

    public function movement()
    {
        return $this->belongsTo('App\Models\Movement');
    }

    public function edition()
    {
        return $this->belongsTo('App\Models\Edition');
    }
}
