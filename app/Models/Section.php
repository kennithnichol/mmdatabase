<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public $fillable = [
        'movement',
        'tempo_text',
        'mm_note',
        'mm_note_dotted',
        'bpm',
        'structural_note',
        'structural_note_dotted',
        'staccato_note',
        'staccato_note_dotted',
        'ornamental_note',
        'ornamental_note_dotted',
    ];

    public function movement()
    {
        return $this->belongsTo(Movement::class);
    }

    public function edition()
    {
        return $this->belongsTo(Edition::class);
    }
}
