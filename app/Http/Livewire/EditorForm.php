<?php

namespace App\Http\Livewire;

use App\Models\Editor;
use Livewire\Component;

class EditorForm extends Component
{
    public $designTemplate = 'bootstrap';
    public $editor;

    public $editorName;

    public $inModal;

    protected $listeners = [
        'editorModalClosed' => 'modalClosed',
    ];

    protected $rules = [
        'editorName' => 'required|unique:editors,name',
    ];

    public function modalClosed()
    {
        $this->reset();
        $this->hydrate();
    }

    public function hyrdate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount(?Editor $editor, bool $inModal = false)
    {
        $this->inModal = $inModal;
        $this->editor = $editor ?? new Editor();

        if ($editor->id) {
            $this->editorName = $editor->name;
        }
    }

    public function save()
    {
        $this->validate();

        $this->editor->name = $this->editorName;
        $this->editor->save();

        $this->emit('editorSaved', $this->editor->id);

        if ($this->inModal)
        {
            $this->emit('changeEditor', $this->editor->id);
            $this->reset();
            $this->inModal = true;
            $this->editor = new Editor();
            return;
        }

        return $this->redirectRoute('home');

    }

    public function render()
    {
        return view('livewire.editor-form');
    }
}
