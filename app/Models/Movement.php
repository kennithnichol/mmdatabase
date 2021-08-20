<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    public function piece()
    {
        return $this->belongsTo('\App\Model\Piece');
    }

    public function sections()
    {
        return $this->hasMany('\App\Model\Section');
    }
}
