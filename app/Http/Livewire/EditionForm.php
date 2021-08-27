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
        $this->composers = Composer::has('pieces')->orderBy('name', 'asc')->get();
        $this->publishers = Publisher::orderBy('name')->get();
        $this->editors = Editor::orderBy('name')->get();

        $this->edition = $edition ?? new Edition();

        $this->sections = $edition->sections;
        $this->piece = $edition->piece->id;
        $this->composer = $edition->composer->id;

        if ($this->composer) { $this->updatedComposer($this->composer); }
        if ($this->piece) { $this->updatedPiece($this->piece); }

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
//        $this->sortSections();

        $this->emit('sectionSaved');
        $this->close();
    }

    public function updateSectionOrder( $orderIds )
    {
        collect($orderIds)->each(function($id) {
            $this->sections->where('id', (int) $id['value'])->order = $id['order'];
        });
        $this->sortSections();
    }

    protected function sortSections()
    {
        $this->sections = $this->sections->sortBy(['movement', 'asc'], ['order', 'asc']);
        $this->sections->each(function($section, $key) {
            $section->order = $key;
        });
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $rehydratedSections = collect();
        foreach($this->sections as $section) {
            if ($section instanceOf Section) {
                $rehydratedSections->push($section);
            } else {
                $rehydratedSections->push(new Section($section));
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

        $this->edition->piece_id = $this->piece;
        $this->edition->save();
        $this->edition->sections()->saveMany($this->sections->all());

        return redirect()->route('edition.index', ['piece' => $this->piece]);
    }

    public function render()
    {
        return view('livewire.edition-form');
    }
}
