<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use App\Models\Status;
use App\Models\Role;
use Livewire\WithPagination;
use Carbon\Carbon;

class Borrows extends Component
{
    use WithPagination;

    public $search = "";
    public $showByPage = 5;
    public $triggerModal = false;
    public $changeStatus = false;
    public $clear = false;
    public $selectedBorrow = null;
    public $book;
    public $resultBooks = [];
    public $user;
    public $userName;
    public $resultUsers = [];
    public $expiredAt;
    public $status;
    public $availableStatus = [];

    protected $listeners = ['reloadTable' => '$refresh', 'resetArrays'];

    protected function rules(){
        return [
            'book' => 'required|exists:books,name',
            'user' => 'required|exists:users,name',
        ];
    }

    protected $messages = [
        'book.required' => 'Select an existent book',
        'book.exists' => 'Select an existent book',
        'user.required' => 'Select an existent user',
        'user.exists' => 'Select an existent user',
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
        $borrows = Borrow::where(function ($query){
            $query->whereHas('user', function ($query2){
                $query2->where('name', 'like', '%'.$this->search.'%');
            })->orWhereHas('book', function ($query2) {
                $query2->where('name', 'like', '%'.$this->search.'%');
            })->orWhereHas('statusObj', function ($query2) {
                $query2->where('name', 'like', '%'.$this->search.'%');
            });
        })->orderBy('expired_at')->whereIn('status', [Status::REQUESTED,Status::BORROWED, Status::EXPIRED])
        ->paginate($this->showByPage);
        return view('livewire.borrows', compact('borrows'));
    }

    /**
     * Open modal to add or create a new borrow
     *
     * @param optional int $id
     * @return  void
     */
    public function openModal($id = null){
        $this->resetErrorBag();
        if($id){
            $borrow = Borrow::findOrFail($id);
            $this->selectedBorrow = $id;
            $this->book = $borrow->book->name;
            $this->user = $borrow->user->name;
            $this->expiredAt = Carbon::parse($borrow->expired_at)->format('Y-m-d');
            $this->status = $borrow->status;
            if($this->status == Status::REQUESTED){
                $status = [Status::REQUESTED,STATUS::BORROWED, Status::NOT_BORROWED];
            }elseif($this->status == Status::BORROWED){
                $status = [Status::BORROWED,STATUS::RETURNED, Status::EXPIRED];
            }elseif($this->status == Status::EXPIRED){
                $status = [Status::EXPIRED,STATUS::RETURNED];
            }
            $this->availableStatus = Status::whereIn('id', $status)->get();
            $this->changeStatus = true;
        }else{
            $this->selectedBorrow = null;
            $this->book = "";
            $this->user = "";
            $this->changeStatus = false;
        }
        $this->emit('autofocus', 'book');
        $this->triggerModal = true;
    }

    /**
     * Add or update the borrow
     * 
     * @param optional int $id
     * @return  void
     */
    public function saveBorrow(){
        $this->validate();
        $book = Book::where('name',$this->book)->first();
        if($this->selectedBorrow){
            $borrow = Borrow::find($this->selectedBorrow);
            $borrow->status = $this->status;
            if(in_array($this->status, [Status::NOT_BORROWED, Status::RETURNED])){
                $book->available = 1;
                $book->save();
            }
            $borrow->save();
            $this->emit('Toast',"Borrow updated");
        }else{
            $user = User::where('name',$this->user)->first();
            $borrow = Borrow::create([
                'book_id' => $book->id,
                'user_id' => $user->id,
                'expired_at' => Carbon::now()->addDays(3), 
                'status'=> Status::BORROWED
            ]);
            $book->available = 0;
            $book->save();
            $this->emit('Toast',"Borrow Added");
        }
        $this->triggerModal = false;
    }

    /**
     * Function to show autocomplete results
     * 
     * @param String $name
     * @param  int  $type
     * @return  void
     */
    public function autocomplete($name, $type){
        $this->resetArrays();
        $name = trim($name);
        switch($type){
            case 1:
                if($name){
                    $books = Book::where('name','like', '%'.$name.'%')->where('available',1)->orderBy('name')->get();
                    if($books->count()){
                        $this->resultBooks = $books;
                    }
                }
                break;
            case 2:
                if($name){
                    $users = User::where('name','like', '%'.$name.'%')->where('role_id',Role::USER)->orderBy('name')->get();
                    if($users->count()){
                        $this->resultUsers = $users;
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
     * Function to set autocomplete input
     * 
     * @param String $name
     * @param  int  $type
     * @return  void
     */
    public function setInput($name, $type, $last=null){
        switch($type){
            case 1:
                if($name){
                    $this->book = $name;
                }
                break;
            case 2:
                if($name){
                    $this->user = $name . " " . $last;
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
        $this->resultUsers = [];
        $this->resultBooks = [];
    }
}
