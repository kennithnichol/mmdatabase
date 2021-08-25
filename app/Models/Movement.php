<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    public function piece()
    {
        return $this->belongsTo(Piece::class);
    }

    public function timeSignature()
    {
        return $this->belongsTo(TimeSignature::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
