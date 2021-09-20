<?php

namespace App\Http\Livewire;

use App\Models\Composer;
use App\Models\Piece;
use Livewire\Component;

class PieceForm extends Component
{
    public $designTemplate = 'bootstrap';
    public $piece;
    public $composer;
    public $title;
    public $year_published;

    public $inModal;

    protected $listeners = [
        'pieceModalClosed' => 'modalClosed',
    ];

    protected $rules = [
        'title' => 'required|unique:pieces,name',
        'year_published' => 'numeric|min:4|nullable'
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

    public function mount(?Piece $piece, Composer $composer, bool $inModal = false)
    {
        $this->composer = $composer;
        $this->inModal = $inModal;
        $this->piece = $piece ?? new Piece();

        if ($piece->id) {
            $this->title = $piece->title;
            $this->year_published = $piece->year_published;
        }
    }

    public function save()
    {
        $this->validate();

        $this->piece->title = $this->title;
        $this->piece->year_published = $this->year_published;
        $this->piece->save();

        $this->emit('pieceSaved', $this->piece->id);

        if ($this->inModal)
        {
            $this->emit('changepiece', $this->piece->id);
            $this->reset();
            $this->inModal = true;
            $this->piece = new Piece();
            return;
        }

        return $this->redirectRoute('home');

    }

    public function render()
    {
        return view('livewire.piece-form');
    }
}
