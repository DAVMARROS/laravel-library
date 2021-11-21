<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use Livewire\WithPagination;

class Books extends Component
{
    use WithPagination;

    public $search = "";
    public $showByPage = 10;
    public $triggerModal = false;
    public $edit = false;
    public $clear = false;
    public $selectedBook = null;
    public $name;
    public $category;
    public $resultCategories = [];
    public $author;
    public $resultAuthors = [];
    public $publicated_at;

    protected $listeners = ['reloadTable' => '$refresh', 'resetArrays'];

    protected function rules(){
        return [
            'name' => 'required',
            'category' => 'required|exists:categories,name',
            'author' => 'required|exists:authors,name',
            'publicated_at' => 'required',
        ];
    }

    protected $messages = [
        'name.required' => 'Name is required',
        'category.required' => 'Select an existent category',
        'category.exists' => 'Select an existent category',
        'author.required' => 'Select an existent author',
        'author.exists' => 'Select an existent author',
        'publicated_at.required' => 'Publication Date is required',
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
        $books = Book::where('name','like', '%'.$this->search.'%')
        ->orderBy('name')->paginate($this->showByPage);
        return view('livewire.books', compact('books'));
    }

    /**
     * Open modal to add or create a new book
     *
     * @param optional int $id
     * @return  void
     */
    public function openModal($id = null){
        $this->resetErrorBag();
        if($id){
            $book = Book::findOrFail($id);
            $this->selectedBook = $id;
            $this->name = $book->name;
            $this->category = $book->category->name;
            $this->author = $book->author->name;
            $this->publicated_at = $book->author;
            $this->edit = true;
        }else{
            $this->selectedBook = null;
            $this->name = "";
            $this->category = "";
            $this->author = "";
            $this->publicated_at = "";
            $this->edit = false;
        }
        $this->emit('autofocus', 'name');
        $this->triggerModal = true;
    }

    /**
     * Add or update the book
     * 
     * @param optional int $id
     * @return  void
     */
    public function saveBook(){
        $this->validate();
        $category = Category::where('name',$this->category)->first();
        $author = Author::where('name',$this->author)->first();
        if($this->selectedBook){
            $book = Book::find($this->selectedBook);
            $book->name = $this->name;
            $book->category_id = $category->id;
            $book->author_id = $author->id;
            $book->publicated_at = $this->publicated_at;
            $book->save();
            $this->emit('Toast',"Book updated");
        }else{
            $book = Book::create([
                'name' => $this->name,
                'category_id' => $category->id,
                'author_id' => $author->id,
                'publicated_at' => $this->publicated_at,
            ]);
            $this->emit('Toast',"Book Added");
        }
        $this->triggerModal = false;
    }

    /**
     * Funcion para mostrar los resultados del autocompletado
     * 
     * @param String $name
     * @param  int  $type
     * @param  optional int  $index
     * @return  void
     */
    public function autocomplete($name, $type, $index = null){
        $this->resetArrays();
        $name = trim($name);
        switch($type){
            case 1:
                if($name){
                    $categories = Category::where('name','like', '%'.$name.'%')->orderBy('name')->get();
                    if($categories->count()){
                        $this->resultCategories = $categories;
                    }
                }
                break;
            case 2:
                if($name){
                    $authors = Author::where('name','like', '%'.$name.'%')->orderBy('name')->get();
                    if($authors->count()){
                        $this->resultAuthors = $authors;
                    }
                }
                break;
        }
        if($this->clear){
            $this->resetArrays();
            $this->clear = !$this->clear;
        }
    }

    /**
     * Funcion para llenar el campo del autocompletado
     * 
     * @param String $name
     * @param  int  $type
     * @param  optional int  $index
     * @return  void
     */
    public function setInput($name, $type){
        switch($type){
            case 1:
                if($name){
                    $this->category = $name;
                }
                break;
            case 2:
                if($name){
                    $this->author = $name;
                }
                break;
        }
        $this->resetArrays();
    }

    /**
     * Funcion para limpiar los errores de validación y el autocompletado
     * 
     * @return  void
     */
    public function resetArrays(){
        $this->resetErrorBag();
        $this->resultCategories = [];
        $this->resultAuthors = [];
    }
}
