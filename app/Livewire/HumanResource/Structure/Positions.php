<?php

namespace App\Livewire\HumanResource\Structure;

use App\Models\Position;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Positions extends Component
{
    // Variables - Start //
    public $positions = [];

    public $position;

    #[Rule('required')]
    public $name;

    #[Rule('required|numeric')]
    public $vacancies_count;

    public $is_edit = false;

    public $confirmedId;

    // Variables - End //
    public function render()
    {
        $this->positions = Position::all();

        return view('livewire.human-resource.structure.positions');
    }

    public function submitPosition()
    {
        $this->is_edit ? $this->editPosition() : $this->addPosition();
    }

    public function addPosition()
    {
        $this->validate();

        Position::create([
            'name' => $this->name,
            'vacancies_count' => $this->vacancies_count,

        ]);

        $this->dispatch('closeModal', elementId: '#positionModal');
        $this->dispatch('toastr', type: 'success'/* , title: 'Done!' */ , message: 'Going Well!');
    }

    public function editPosition()
    {
        $this->validate();

        $this->position->update([
            'name' => $this->name,
            'vacancies_count' => $this->vacancies_count,

        ]);

        $this->dispatch('closeModal', elementId: '#positionModal');
        $this->dispatch('toastr', type: 'success'/* , title: 'Done!' */ , message: 'Going Well!');

        $this->reset();
    }

    public function confirmDeletePosition($id)
    {
        $this->confirmedId = $id;
    }

    public function deletePosition(Position $position)
    {
        $position->delete();
        $this->dispatch('toastr', type: 'success'/* , title: 'Done!' */ , message: 'Going Well!');
    }

    public function showNewPositionModal()
    {
        $this->reset();
    }

    public function showEditPositionModal(Position $position)
    {
        $this->reset();
        $this->is_edit = true;

        $this->position = $position;

        $this->name = $position->name;
        $this->vacancies_count = $position->vacancies_count;

    }
}
