<?php

namespace App\Livewire\Forms;

use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ShiftForm extends Form
{
    public ?Shift $shift = null;

    public string $name = '';
    public ?string $start_time = null;  // ← WAJIB: ?string agar sesuai input time
    public ?string $end_time = null;    // ← WAJIB: ?string & nullable

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shifts', 'name')->ignore($this->shift?->id),
            ],
            'start_time' => ['required', 'date_format:H:i'], // validasi format jam
            'end_time'   => ['required', 'date_format:H:i'],
        ];
    }

    /**
     * Isi form saat edit
     */
    public function setShift(Shift $shift): self
    {
        $this->shift = $shift;
        $this->name = $shift->name;
        $this->start_time = $shift->start_time; // otomatis string jika kolom time
        $this->end_time = $shift->end_time;

        return $this;
    }

    /**
     * Simpan shift baru
     */
    public function store(): void
    {
        if (! Auth::user()?->isAdmin()) {
            abort(403, 'Hanya admin yang dapat membuat shift baru.');
        }

        $this->validate();

        Shift::create($this->only(['name', 'start_time', 'end_time']));

        $this->reset();
    }

    /**
     * Update shift existing
     */
    public function update(): void
    {
        if (! Auth::user()?->isAdmin()) {
            abort(403, 'Hanya admin yang dapat mengubah shift.');
        }

        $this->validate();

        $this->shift->update($this->only(['name', 'start_time', 'end_time']));
    }

    /**
     * Hapus shift
     */
    public function delete(): void
    {
        if (! Auth::user()?->isAdmin()) {
            abort(403, 'Hanya admin yang dapat menghapus shift.');
        }

        $this->shift->delete();
        $this->reset();
    }
}