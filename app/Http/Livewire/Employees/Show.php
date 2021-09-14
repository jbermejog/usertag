<?php

namespace App\Http\Livewire\Employees;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;


class Show extends Component
{
    use WithPagination;

    public $active;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $item;

    public $confirmingItemDeletion = false;
    public $confirmingItemAdd = false;

    protected $queryString = [
        'active' => ['except' => false],
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];

    public function rules()
    {
        if (isset($this->item->id)) {

            return [
                'item.email' => 'required|unique:users,email,' . $this->item->id,
                'item.name' => 'required|string|min:4',
            ];
        } else {
            return [
                'item.name' => 'required|string|min:4',
                'item.email' => 'required|unique:users,email',

            ];
        }
    }

    protected $rules = [];

    public function render()
    {

        $items = User::when($this->q, function ($query) {
            return $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->q . '%');
            });
        })
           ->orderBy($this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');


        $items = $items->paginate(10);

        $totalUsers = User::count();


        return view('livewire.employees.show', [
            'items' => $items,
        ]);
    }

    public function updatingActive()
    {
        $this->resetPage();
    }

    public function updatingQ()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }

    public function confirmItemDeletion($id)
    {
        $this->confirmingItemDeletion = $id;
    }

    public function deleteItem(User $item)
    {
            $item->delete();
            $this->confirmingItemDeletion = false;
            session()->flash('message', 'Trabajador eliminado correctamente');
    }

    public function confirmItemAdd()
    {
        $this->reset(['item']);
        $this->confirmingItemAdd = true;
    }

    public function confirmItemEdit(User $item)
    {
        $this->resetErrorBag();
        $this->item = $item;

        $this->confirmingItemAdd = true;
    }

    public function saveItem()
    {
        $this->validate();

        if (isset($this->item->id)) {
            $this->item->save();
            $mens = "Empleado guardado correctamente";
        } else {

            $new = User::create([
                'name' => $this->item['name'],
                'email' => $this->item['email'],
                'password' => bcrypt('123456'),
            ]);

            $mens = "Trabajador agregado correctamente";
        }

        session()->flash('message', $mens);

        $this->confirmingItemAdd = false;

    }

    public function toggleState(User $item): void
    {
        $item->status = !$item->status;
        $item->save();
    }

}
