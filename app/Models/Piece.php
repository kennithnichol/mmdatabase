<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    use HasFactory;

    public function composer()
    {
        return $this->belongsTo(Composer::class);
    }

    public function editions()
    {
        return $this->hasMany(Edition::class);
    }

    public function editors()
    {
        return $this->hasManyThrough(Editor::class, Edition::class);
    }

    public function publishers()
    {
        return $this->hasManyThrough(Publisher::class, Edition::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
