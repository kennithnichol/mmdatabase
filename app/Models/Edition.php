<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    use HasFactory;

    public function editor()
    {
        return $this->hasOne('App\Models\Editor');
    }

    public function publisher()
    {
        return $this->hasOne('App\Models\Publisher');
    }

    public function composer()
    {
        return $this->hasOne('App\Models\Composer');
    }

    public function sections()
    {
        return $this->hasMany('App\Models\Section');
    }
}
