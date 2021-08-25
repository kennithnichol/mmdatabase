<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    use HasFactory;

    public function piece()
    {
        return $this->belongsTo(Piece::class);
    }

    public function editor()
    {
        return $this->belongsTo(Editor::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function composer()
    {
        return $this->hasOneThrough(Composer::class, Piece::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
