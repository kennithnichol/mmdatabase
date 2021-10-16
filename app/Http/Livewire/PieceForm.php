<?php

namespace App\Http\Livewire;

use App\Models\Composer;
use App\Models\Movement;
use App\Models\Piece;
use Livewire\Component;

class PieceForm extends Component
{
	public $designTemplate = 'bootstrap';
	public $piece;
	public $composer;
	public $title;
	public $year_composed;
	public $movements;
	public $deletedMovements;

	public $inModal;
	public $showModal = false;
	public $isEditing = false;
	public $movement;
	public $editIndex;


	protected $listeners = [
		'pieceModalClosed' => 'modalClosed',
	];

	public function rules(): array
	{
		if ($this->showModal) {
			return [
				'movement.order' => 'required|integer',
				'movement.number' => 'required|integer',
				'movement.title' => '',
			];
		}
		return [
			'title' => 'required',
			'year_composed' => 'numeric|min:4|nullable',
			'movements' => ''
		];
	}

	public function modalClosed()
	{
		$this->reset();
		$this->hydrate();
	}

	public function mount(?Piece $piece, ?int $composer, bool $inModal = false)
	{
		$this->composer = $composer;
		$this->inModal = $inModal;
		$this->piece = $piece ?? new Piece();
		$this->year_composed = null;
		$this->movement = null;

		$this->movements = collect();
		$this->deletedMovements = collect();

		if ($piece->id) {
			$this->title = $piece->title;
			$this->year_composed = $piece->year_composed;
			$this->movements = $piece->movements;
			$this->movements = $this->movements->sortBy('order');
		}
	}

	public function addMovement()
	{
		$this->movement = new Movement();
		$this->movement['order'] = $this->movements->count() + 1;
		$this->showModal = true;
	}

	public function editMovement($index)
	{
		$this->showModal = true;
		$this->isEditing = true;
		$this->editIndex = $index;
		$this->movement = $this->movements->get($index);
	}

	public function removeMovement($index)
	{
		$this->deletedMovements->push($this->movements->get($index));
		$this->movements->forget($index);
	}

	public function saveMovement()
	{
		$this->validate();

		if ($this->isEditing) {
			$this->movements[$this->editIndex] = $this->movement;
		} else {
			$this->movements->push($this->movement);
		}

		$this->emit('movementSaved');
		$this->close();
	}

	public function hydrate()
	{
		$this->resetErrorBag();
		$this->resetValidation();

		$this->movements = $this->rehydrateMovements($this->movements);
		$this->deletedMovements = $this->rehydrateMovements($this->deletedMovements);
	}

	protected function rehydrateMovements($movements)
	{
		$rehydrtatedMovements = collect();

		foreach($movements as $movement) {
			if ($movement instanceof Movement) {
				$rehydrtatedMovements->push($movement);
			} else {
				if (isset($movement['id'])) {
					$rehydrtatedMovements->push(Movement::find($movement['id']));
				} else {
					$rehydrtatedMovements->push(new Movement($movement));
				}
			}
		}

		return $rehydrtatedMovements;
	}

	public function close()
	{
		$this->showModal = false;
		$this->isEditing = false;
	}

	public function save()
	{
		$this->validate();

		$this->movements = $this->movements->map(function ($movement, $key) {
			$movement->order = $key + 1;
			return $movement;
		});


		$this->piece->title = $this->title;
		$this->piece->year_composed = $this->year_composed;
		$this->piece->composer_id = $this->composer;
		$this->piece->save();

		$this->piece->movements()->saveMany($this->movements->all());

		$this->deletedMovements->each(function ($movement) {
			$movement->delete();
		});

		$this->emit('pieceSaved', $this->piece->id);

		if ($this->inModal) {
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
