<?php

namespace App\Livewire\Forms;

use App\Models\Shift;
use Livewire\Form;
use Livewire\Attributes\Rule;
use Illuminate\Support\Carbon;

class ShiftForm extends Form
{
    public ?Shift $shift;

    #[Rule('required|string')]
    public $name = '';

    #[Rule('required')] 
    public $start_time = '';

    #[Rule('required')] 
    public $end_time = '';

    public function setShift(Shift $shift)
    {
        $this->shift = $shift;
        $this->name = $shift->name;
        $this->start_time = $shift->start_time;
        $this->end_time = $shift->end_time;
        
        // --- PERBAIKAN PENTING DISINI ---
        // Kita harus mengembalikan '$this' agar bisa disambung ->delete()
        return $this; 
    }

    public function store()
    {
        $this->validate();

        Shift::create([
            'name' => $this->name,
            'start_time' => Carbon::parse($this->start_time)->format('H:i:s'),
            'end_time'   => Carbon::parse($this->end_time)->format('H:i:s'),
        ]);

        $this->reset();
    }

    public function update()
    {
        $this->validate();

        $this->shift->update([
            'name' => $this->name,
            'start_time' => Carbon::parse($this->start_time)->format('H:i:s'),
            'end_time'   => Carbon::parse($this->end_time)->format('H:i:s'),
        ]);
    }

    public function delete()
    {
        if ($this->shift) {
            $this->shift->delete();
        }
    }
}