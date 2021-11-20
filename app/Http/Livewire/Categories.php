<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    public $search = "";
    public $showByPage = 10;
    public $triggerModal = false;
    public $edit = false;
    public $selectedCategory = null;
    public $name;
    public $description;

    protected $listeners = ['reloadTable' => '$refresh'];

    protected function rules(){
        return [
            'name' => 'required',
            'description' => 'required',
        ];
    }

    protected $messages = [
        'name.required' => 'Name is required',
        'description.required' => 'Description is required',
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
        $categories = Category::where('name','like', '%'.$this->search.'%')
        ->orderBy('name')->paginate($this->showByPage);
        return view('livewire.categories', compact('categories'));
    }

    /**
     * Open modal to add or create a new category
     *
     * @param optional int $id
     * @return  void
     */
    public function openModal($id = null){
        $this->resetErrorBag();
        if($id){
            $category = Category::findOrFail($id);
            $this->selectedCategory = $id;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->edit = true;
        }else{
            $this->selectedCategory = null;
            $this->name = "";
            $this->description = "";
            $this->edit = false;
        }
        $this->emit('autofocus', 'name');
        $this->triggerModal = true;
    }

    /**
     * Add or update the category
     * 
     * @param optional int $id
     * @return  void
     */
    public function saveCategory(){
        $this->validate();
        if($this->selectedCategory){
            $category = Category::find($this->selectedCategory);
            $category->name = $this->name;
            $category->description = $this->description;
            $category->save();
            $this->emit('Toast',"Category updated");
        }else{
            $category = new Category();
            $category->name = $this->name;
            $category->description = $this->description;
            $category->save();
            $this->emit('Toast',"Category Added");
        }
        $this->triggerModal = false;
    }
}
