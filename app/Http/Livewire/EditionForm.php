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

    // the currently edited section
    public $showModal = false;
    public $isEditing = false;
    public $canChangePiece = true;
    public $section;
    public $editIndex;

    public function rules(): array
    {
        if ($this->showModal) {
            return [
                'section.order' => 'required|integer',
                'section.movement_id' => 'required',
                'section.tempo_text' => '',
                'section.mm_note' => 'required',
                'section.mm_note_dotted' => 'boolean',
                'section.bpm' => 'required|integer',
                'section.structural_note' => '',
                'section.structural_note_dotted' => 'boolean',
                'section.staccato_note' => '',
                'section.staccato_note_dotted' => 'boolean',
                'section.ornamental_note' => '',
                'section.ornamental_note_dotted' => 'boolean',
            ];
        }

        return [
            'composer' => 'required',
            'piece' => 'required',
            'publisher' => '',
            'editor' => '',
            'sections' => '',
            'year_published' => 'nullable|digits:4',
            'link' => 'url|nullable',
        ];
    }

    public function mount(Edition $edition)
    {
        $this->edition = $edition ?? new Edition();

        $this->composers = Composer::orderBy('name', 'asc')->get();
        $this->publishers = Publisher::orderBy('name')->get();
        $this->editors = Editor::orderBy('name')->get();        

        $this->pieces = collect();
        $this->sections = collect();
        $this->movements = collect();

        if ($edition->id) {
            $this->sections = $edition->sections;
            $this->canChangePiece = false;
            $this->piece = $edition->piece->id;
            $this->composer = $edition->composer->id;
            $this->publisher = $edition->publisher;
            $this->editor = $edition->editor;
        }        

        if ($this->composer) {
            $this->updatedComposer($this->composer);
        }
        if ($this->piece) {
            $this->updatedPiece($this->piece);
        }
    }

    public function addSection()
    {
        $this->section = new Section();
        $this->section['order'] = $this->sections->count() + 1;
        $this->showModal = true;
    }

    public function editSection($index)
    {
        $this->showModal = true;
        $this->isEditing = true;
        $this->editIndex = $index;
        $this->section = $this->sections->get($index);
    }

    public function removeSection($index)
    {
        $this->sections->get($index)->delete();
        $this->sections->forget($index);
    }

    public function updatedComposer($composer_id)
    {
        if (empty($composer_id)) {
            $this->pieces = collect();
            $this->piece = null;
            return;
        }
        $this->pieces = Piece::where('composer_id', $composer_id)->orderBy('title')->get();
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

        // force boolean values on dotted fields (can't be null!)
        $this->section['mm_note_dotted'] = (bool) $this->section['mm_note_dotted'];
        $this->section['structural_note_dotted'] = (bool) $this->section['structural_note_dotted'];
        $this->section['staccato_note_dotted'] = (bool) $this->section['staccato_note_dotted'];
        $this->section['ornamental_note_dotted'] = (bool) $this->section['ornamental_note_dotted'];

        if ($this->isEditing) {
            $this->sections[$this->editIndex] = $this->section;
        } else {
            $this->sections->push($this->section);
        }

        $this->canChangePiece = false;

        $this->emit('sectionSaved');
        $this->close();
    }

    public function updateSectionOrder($orderIds)
    {
        // ref: https://laravel-livewire.com/screencasts/s8-dragging-list

        
    }

    protected function sortSections()
    {
        // $this->sections = $this->sections->sortBy(['movement', 'asc']);
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $rehydratedSections = collect();
        
        foreach ($this->sections as $section) {
            if ($section instanceof Section) {
                $rehydratedSections->push($section);
            } else {
                if ($section['id']){
                $rehydratedSections->push(Section::find($section->id));
                }else{
                $rehydratedSections->push(new Section($section->id));
                }
            }
        }
        $this->sections = $rehydratedSections;
    }

    public function close()
    {
        $this->showModal = false;
        $this->isEditing = false;
    }

    public function save()
    {
        $this->validate();

        dd($this->sections->map(function ($section) {
            return $section->id;
        }));

        $this->edition->piece_id = $this->piece;
        $this->edition->save();
        
        $this->edition->sections()->saveMany($this->sections->all());

        dd($this->sections->map(function ($section) {
            return $section->id;
        }));

        return redirect()->route('edition.index', ['piece' => $this->piece]);
    }

    public function render()
    {
        return view('livewire.edition-form');
    }
}
