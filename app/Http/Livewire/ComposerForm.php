<?php

namespace App\Http\Livewire;

use App\Models\Composer;
use Livewire\Component;

class ComposerForm extends Component
{
    public $designTemplate = 'bootstrap';
    public $composer;

    public $composerName;
    public $born;
    public $deceased;

    public $inModal;

    protected $listeners = [
        'composerModalClosed' => 'modalClosed',
    ];

    protected $rules = [
        'composerName' => 'required|unique:composers,name',
        'born' => 'integer|min:4|nullable',
        'deceased' => 'integer|min:4|nullable'
    ];

    public function modalClosed()
    {
        $this->reset();
        $this->hydrate();
    }
    
    public function mount(?Composer $composer, $inModal = false)
    {
        $this->inModal = $inModal;

        $this->composer = $composer ?? new Composer();

        if ($composer->id) {
            $this->composerName = $composer->name;
            $this->born = $composer->born;
            $this->deceased = $composer->deceased;
        }
    }

    public function save()
    {
        $this->validate();        

        $this->composer->name = $this->composerName;
        $this->composer->born = $this->born;
        $this->composer->deceased = $this->deceased;
        $this->composer->save();

        $this->emit('composerSaved', $this->composer->id);

        if ($this->inModal) {
            $this->emit('changeComposer', $this->composer->id);
            $this->reset();
            $this->inModal = true;
            $this->composer = new Composer();
            return;
        }

        return $this->redirectRoute('home');
    }

    public function render()
    {
        return view('livewire.composer-form');
    }
}
