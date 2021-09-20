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
    public $deletedSections;

    public $piece;
    public $composer;
    public $publisher;
    public $editor;
    public $year_published;
    public $link;

    // the currently edited section
    public $showModal = false;
    public $showComposerModal = false;
    public $showPieceModal = false;
    public $showPublisherModal = false;
    public $showEditorModal = false;
    public $isEditing = false;
    public $canChangePiece = true;
    public $section;
    public $editIndex;

    protected $listeners = [
        'composerSaved',
        'pieceSaved'
    ];

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
        $this->deletedSections = collect();
        $this->movements = collect();

        if ($edition->id) {
            $this->sections = $edition->sections;
            $this->sections = $this->sections->sortBy('order');
            $this->canChangePiece = false;
            $this->piece = $edition->piece->id;
            $this->composer = $edition->composer->id;
            $this->publisher = $edition->publisher->id;
            $this->editor = $edition->editor->id;
        }        

        if ($this->composer) {
            $this->updatedComposer($this->composer);
        }
        if ($this->piece) {
            $this->updatedPiece($this->piece);
        }
    }
    
    public function composerSaved(?int $composer)
    {
        $this->composers = Composer::orderBy('name', 'asc')->get();
        if (!empty($composer)) {
            $this->composer = $composer;
        }
    }

    public function editorSaved(?int $editor)
    {
        $this->editors = Editor::orderBy('name', 'asc')->get();
        if (!empty($editor)) {
            $this->editor = $editor;
        }
    }

    public function pieceSaved(?int $piece)
    {
        $this->pieces = Piece::where('composer_id', $this->composer)->orderBy('title')->get();
        if (!empty($piece)) {
            $this->piece = $piece;
        }
    }
    public function addSection()
    {
        $this->section = new Section();
        $this->section['order'] = $this->sections->count() + 1;
        $this->showModal = true;
        
    }

    public function addComposer()
    {
        $this->showComposerModal = true;
    }

    public function closeComposer()
    {
        $this->emit('composerModalClosed');
        $this->showComposerModal = false;
    }

    public function addPiece()
    {
        $this->showPieceModal = true;
    }

    public function closePiece()
    {
        $this->showPieceModal = false;
    }

    public function addPublisher()
    {
        $this->showPublisherModal = true;
    }

    public function closePublisher()
    {
        $this->showPublisherModal = false;
    }

    public function addEditor()
    {
        $this->showEditorModal = true;
    }

    public function closeEditor()
    {
        $this->emit('editorModalClosed');
        $this->showEditorModal = false;
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
        $this->deletedSections->push($this->sections->get($index));
        $this->sections->forget($index);
    }

    public function updatedComposer($composer_id)
    {
        $this->emit('updatedComposer', $composer_id);
        if (empty($composer_id)) {
            $this->pieces = collect();
            $this->piece = '';
            return;
        }
        $this->pieces = Piece::where('composer_id', $composer_id)->orderBy('title')->get();
        if ($this->pieces->count() > 0) {
            $this->piece = $this->pieces->first()->id;
        } else {
            $this->piece = '';
        }
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

        $this->sortSections();

        $this->canChangePiece = false;

        $this->emit('sectionSaved');
        $this->close();
    }

    public function updateSectionOrder($orderIds)
    {        
        $this->sections = collect($orderIds)->map(function ($id) {
            return $this->sections->where('id', (int) $id['value'])->first();
        });
        $this->sortSections();
    }

    protected function sortSections()
    {
        $this->sections = $this->sections->sortBy(['movement', 'asc']);
    }


    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        
        
        $this->sections = $this->rehydrateSections($this->sections);
        $this->deletedSections = $this->rehydrateSections($this->deletedSections);
    }

    protected function rehydrateSections($sections)
    {
        $rehydratedSections = collect();

        foreach ($sections as $section) {
            if ($section instanceof Section) {
                $rehydratedSections->push($section);
            } else {
                if (isset($section['id'])) {
                    $rehydratedSections->push(Section::find($section['id']));
                } else {
                    $rehydratedSections->push(new Section($section));
                }
            }
        }

        return $rehydratedSections;
    }

    public function close()
    {
        $this->showModal = false;
        $this->isEditing = false;
    }

    public function save()
    {
        $this->validate();

        $this->sections = $this->sections->map(function ($section, $key) {
            $section->order = $key + 1;
            return $section;
        });
        
        $this->edition->piece_id = $this->piece;
        $this->edition->save();        
        
        $this->edition->sections()->saveMany($this->sections->all());

        $this->deletedSections->each(function($section) {
            $section->delete();
        });

        return redirect()->route('edition.index', ['piece' => $this->piece]);
    }

    public function render()
    {
        return view('livewire.edition-form');
    }
}
