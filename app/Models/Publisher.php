<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    public function pieces()
    {
        return $this->hasManyThrough(Piece::class, Edition::class);
    }

    public function composers()
    {
        return $this->hasManyThrough(Composer::class, Edition::class);
    }

    public function editors()
    {
        return $this->hasManyThrough(Editor::class, Edition::class);
    }
}
