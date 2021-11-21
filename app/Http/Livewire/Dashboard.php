<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Book;

class Dashboard extends Component
{

    public $resultsBooks = [];
    public $clear = false;
    public $book;
    public $search = "";
    public $triggerModal = false;

    protected $listeners = ['resetBooks', 'closeModal'];

    public function render()
    {
        $categories = Category::all();
        return view('livewire.dashboard', compact('categories'));
    }

    public function searchBook($name){
        $this->resultsBooks = [];
        if($name){
            $books = Book::where('name','like', '%'.$name.'%')->orderBy('name')->get();
            if($books->count()){
                $this->resultsBooks = $books;
            }
        }
        if($this->clear){
            $this->resultsBooks = [];
            $this->clear = !$this->clear;
        }
    }

    public function showBook($name){
        if($name){
            $this->book = Book::where('name',$name)->first();
        }
        $this->resultsBooks = [];
        $this->triggerModal = true;
        $this->search = "";
    }

    public function resetBooks(){
        $this->resultsBooks = [];
    }

    public function closeModal(){
        $this->triggerModal = false;
    }
}
