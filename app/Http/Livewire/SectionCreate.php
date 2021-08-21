<?php

namespace App\Http\Livewire;

use App\Models\Edition;
use App\Models\Movement;
use App\Models\Piece;
use App\Models\Section;
use Livewire\Component;

class SectionCreate extends Component
{

    public $sections = [];
    public $movements = [1];
    public $pieces = [];
    public $piece = null;

    public $saved = false;
    
    public function mount()
    {
        $this->pieces = Piece::all();
        $this->sections = [];
    }

    public function render()
    {
        return view('livewire.section-create');
    }

    public function addSection()
    {
        $this->sections[] = '';
    }

    public function updateOrder($orderList)
    {
        if (!$this->saved) return;
        foreach($orderList as $item) {
            Section::find($item['value'])->update(['order' => $item['order']]);
        }
    }

    public function updateMovements($piece)
    {
        if ($this->piece) {
            $this->movements = Movement::whereKey('piece_id', $this->piece)->get();
        } else {
            $this->movements = [1];
        }
    }
}
