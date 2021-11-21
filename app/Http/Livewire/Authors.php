<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Author;
use Livewire\WithPagination;

class Authors extends Component
{
    use WithPagination;

    public $search = "";
    public $showByPage = 5;
    public $triggerModal = false;
    public $edit = false;
    public $selectedAuthor = null;
    public $name;

    protected $listeners = ['reloadTable' => '$refresh'];

    protected function rules(){
        return [
            'name' => 'required',
        ];
    }

    protected $messages = [
        'name.required' => 'Name is required',
    ];

    /**
     * Updete the component on search 
     */
    public function updatingSearch(){
        $this->resetPage();
    }

    /**
     * Update the component on change 
     */
    public function updatingShowByPage(){
        $this->resetPage();
    }

    /**
     * Render the component
     */
    public function render()
    {
        $authors = Author::where('name','like', '%'.$this->search.'%')
        ->orderBy('name')->paginate($this->showByPage);
        return view('livewire.authors', compact('authors'));
    }

    /**
     * Open modal to add or create a new author
     *
     * @param optional int $id
     * @return  void
     */
    public function openModal($id = null){
        $this->resetErrorBag();
        if($id){
            $author = Author::findOrFail($id);
            $this->selectedAuthor = $id;
            $this->name = $author->name;
            $this->edit = true;
        }else{
            $this->selectedAuthor = null;
            $this->name = "";
            $this->edit = false;
        }
        $this->emit('autofocus', 'name');
        $this->triggerModal = true;
    }

    /**
     * Add or update the author
     * 
     * @param optional int $id
     * @return  void
     */
    public function saveAuthor(){
        $this->validate();
        if($this->selectedAuthor){
            $author = Author::find($this->selectedAuthor);
            $author->name = $this->name;
            $author->save();
            $this->emit('Toast',"Author updated");
        }else{
            $author = new Author();
            $author->name = $this->name;
            $author->save();
            $this->emit('Toast',"Author Added");
        }
        $this->triggerModal = false;
    }
}
