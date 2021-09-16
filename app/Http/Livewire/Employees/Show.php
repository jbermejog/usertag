<?php

namespace App\Http\Livewire\Employees;

use App\Models\User;
use App\Models\Tag;
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

    public $tags = []; //lista de todos los Tags de la DB
    public $selectedTags = [];  //array con los tags que pueda tener el usuario

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
                // 'item.tags' =>'',
            ];
        } else {
            return [
                'item.name' => 'required|string|min:4',
                'item.email' => 'required|unique:users,email',
                // 'item.tags' =>'',

            ];
        }
    }

    protected $rules = [];


    public function mount(){
        $this->tags = Tag::all(); //montamos el componente con la lista de Tags de la DB
    }

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
            $item->tags()->detach(); //añadido metodo para borrar los datos de la tabla pivote
            $item->delete();
            $this->confirmingItemDeletion = false;
            session()->flash('message', 'Trabajador eliminado correctamente');

            $this->reset(['item']); //reseteamos el item para que no persista
    }

    public function confirmItemAdd()
    {
        $this->reset(['item']);
        $this->confirmingItemAdd = true;
        $this->selectedTags = []; //limpiamos la variable para que no persista en Livewire
    }

    public function confirmItemEdit(User $item)
    {
        $this->resetErrorBag();
        $this->item = $item;

        $this->selectedTags = collect($item->tags()->pluck('tag_id')); //asignamos la variable de Livewire a los Tags del user

        $this->confirmingItemAdd = true;
    }

    public function saveItem()
    {
        $this->validate();

        if (isset($this->item->id)) {

            $this->item->save();
            $this->item->tags()->sync($this->selectedTags); //sincronizamos los tags con los tags del usuario de la DB
            $mens = "Empleado guardado correctamente";
            $this->selectedTags = [];  //tras guardarlos limpiamos la variable para que no persista en Livewire

        } else {

            $new = User::create([
                'name' => $this->item['name'],
                'email' => $this->item['email'],
                'password' => bcrypt('123456'),
            ]);
            $new->tags()->sync($this->selectedTags); //sincronizamos los tags con los tags del usuario de la DB
            $this->selectedTags = []; //tras guardar el nuevo usuario limpiamos la variable para que no persista en Livewire

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
