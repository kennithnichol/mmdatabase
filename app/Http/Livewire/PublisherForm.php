<?php

namespace App\Http\Livewire;

use App\Models\Publisher;
use Livewire\Component;

class PublisherForm extends Component
{
    public $designTemplate = 'bootstrap';
    public $publisher;

    public $publisherName;

    public $inModal;

    protected $listeners = [
        'publisherModalClosed' => 'modalClosed',
    ];

    protected $rules = [
        'publisherName' => 'required|unique:publishers,name',
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

    public function mount(?Publisher $publisher, bool $inModal = false)
    {
        $this->inModal = $inModal;
        $this->publisher = $publisher ?? new Publisher();

        if ($publisher->id) {
            $this->publisherName = $publisher->name;
        }
    }

    public function save()
    {
        $this->validate();

        $this->publisher->name = $this->publisherName;
        $this->publisher->save();

        $this->emit('publisherSaved', $this->publisher->id);

        if ($this->inModal)
        {
            $this->emit('changePublisher', $this->publisher->id);
            $this->reset();
            $this->inModal = true;
            $this->publisher = new Publisher();
            return;
        }

        return $this->redirectRoute('home');

    }

    public function render()
    {
        return view('livewire.publisher-form');
    }
}
