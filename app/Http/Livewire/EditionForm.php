<?php

namespace App\Http\Livewire;

use App\Models\Composer;
use App\Models\Edition;
use App\Models\Editor;
use App\Models\Movement;
use App\Models\Piece;
use App\Models\Publisher;
use App\Models\Section;
use Livewire\Component;

class EditionForm extends Component
{
    public $designTemplate = 'bootstrap';

    public Edition $edition;
    public $pieces;
    public $composers;
    public $publishers;
    public $editors;
    public $movements;
    public $sections;
    
    public $piece;
    public $composer;
    public $publisher;
    public $editor;
    public $year_published;
    public $link;
    
    // currently edited section
    public $showModal = false;
    public $isEditing = false;
    public $section;
    public $editIndex;

    public function rules()
    {
        if ( $this->showModal ) {
            return [
                'section.movement' => 'required',
                'section.tempo_text' => '',
                'section.mm_note' => 'required',
                'section.mm_note_dotted' => '',
                'section.bpm' => 'required|integer',
                'section.structural_note' => '',
                'section.structural_note_dotted' => '',
                'section.stacatto_note' => '',
                'section.stacatto_note_dotted' => '',
                'section.ornamental_note' => '',
                'section.ornamental_note_dotted' => '',
            ];
        }

        return [
            'composer' => 'required',
            'piece' => 'required',
            'publisher' => '',
            'editor' => '',
            'sections' => '',
            'year_published' => 'nullable|integer|max:4|min:4',
            'link' => 'url|nullable',
        ];
    }

    public function mount()
    {
        $this->edition = $edition ?? new Edition();
        $this->composers = Composer::has('pieces')->get();
        $this->publishers = Publisher::all();
        $this->editors = Editor::all();
        $this->pieces = collect();
        $this->movements = collect();
        $this->sections = collect();

        $this->section = new Section();        
    }

    public function addSection()
    {
        $this->section = new Section();
        $this->showModal = true;        
    }

    public function editSection($index)
    {
        $this->showModal = true;
        $this->isEditing = true;
        $this->editIndex = $index;
        $this->section = $this->sections[$index];
    }

    public function removeSection($index)
    {
        $this->sections->forget($index);
    }

    public function updatedComposer($composer_id) {
        if (empty($composer_id)) {
            $this->pieces = collect();
            $this->piece = null;
            return;
        }

        $this->pieces = Piece::where('composer_id', $composer_id)->get();
        $this->piece = $this->pieces->first()->id;
        $this->updatedPiece($this->piece);
    }

    public function updatedPiece($piece_id)
    {
        if (empty($piece_id)) {
            $this->movements = collect();
            return;
        }

        $this->movements = Movement::where('piece_id', $piece_id)->get();
    }

    public function saveSection()
    {
        $this->validate();
        if ( $this->isEditing ) {
            $this->sections[$this->editIndex] = $this->section;
        } else {
            $this->sections->push($this->section);
        }
        $this->close();
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function close()
    {
        $this->showModal = false;
        $this->isEditing = false;
    }

    public function save()
    {
        $this->validate();

        $this->edition->piece_id = $this->piece;
        $this->edition->save();
        $this->edition->sections()->saveMany($this->sections);

        return redirect()->route('edition.index', ['piece' => $this->piece]);
    }

    public function render()
    {
        return view('livewire.edition-form');
    }
}
