<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="block relative w-full md:w-2/3 lg:w-2/3 my-5 mx-10">
                    <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                        <i class="fas fa-search"></i>
                    </span>
                    <input placeholder="Search Book" wire:keydown.tab="$set('clear',1)" wire:keydown.debounce.100ms="searchBook($event.target.value,1)"
                     onfocusout="resetBooks()" wire:model="search"
                    class="appearance-none rounded border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
                    @if(count($resultsBooks))
                        <div class="flex flex-col" style="width:50%; position: fixed;z-index: 200; background-color: white; overflow-y: auto; max-height: 48%;">
                        @foreach($resultsBooks as $result)
                            <div class="cursor-pointer rounded-t border hover:bg-gray-100" wire:click="showBook('{{$result->name}}')">
                                <div class="flex items-center p-2 pl-2 border-transparent border relative hover:bg-gray-100">
                                    <div class="items-center flex">
                                        <div class="mx-2 -mt-1">{{$result->name}}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @endif
                </div>
                <div class="text-center font-semibold text-2xl">Categories</div>
                <div class="py-5 flex flex-wrap items-center justify-center">
                    @foreach($categories as $category)
                        <div class="m-5">
                            <a href="{{ route('categories.show', ['category' => $category->id]) }}">
                                <div class="flex-shrink-0 relative overflow-hidden rounded-lg max-w-xs shadow-lg bg-gray-100 hover:bg-gray-300 hover:scale-105 transform transition duration-300">
                                    <div class="relative px-6 pb-6 mt-6">
                                        <div class="flex justify-center">
                                            <span class="block font-semibold text-xl">{{ $category->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-jet-dialog-modal wire:model="triggerModal">
        <x-slot name="title">
            <h3>Show Book</h3>
        </x-slot>
        <form wire:submit.prevent="submit">
            <x-slot name="content">
                @if($book)
                    <div class="flex flex-col md:flex-row lg:flex-row mb-2">
                        <div class="flex-1 font-bold">Name</div>
                        <div class="flex-1 w-full  border border-gray-300 rounded pl-3 py-2">{{$book->name}}</div>
                    </div>
                    <div class="flex flex-col md:flex-row lg:flex-row mb-2">
                        <div class="flex-1 font-bold">Category</div>
                        <div class="flex-1 w-full border border-gray-300 rounded pl-3 py-2">{{$book->category->name}}</div>
                    </div>
                    <div class="flex flex-col md:flex-row lg:flex-row mb-2">
                        <div class="flex-1 font-bold">Author</div>
                        <div class="flex-1 w-full border border-gray-300 rounded pl-3 py-2">{{$book->author->name}}</div>
                    </div>
                    <div class="flex flex-col md:flex-row lg:flex-row mb-2">
                        <div class="flex-1 font-bold">Publication date</div>
                        <div class="flex-1 w-full border border-gray-300 rounded pl-3 py-2">{{$book->publicated_at}}</div>
                    </div>
                    <div class="flex flex-col md:flex-row lg:flex-row mb-2">
                        <div class="flex-1 font-bold">Available</div>
                        <div class="flex-1 pl-3 py-2">
                            @if ($book->available)
                                <i class="fas fa-check"></i>                                     
                            @else
                                <i class="fas fa-times"></i>
                            @endif
                        </div>
                    </div>
                @endif
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button class="bg-gray-300" wire:click="$toggle('triggerModal')" wire:loading.attr="disabled">
                {{ __('Close') }}
                </x-secondary-button>
                @if($book && Auth::check() && Auth::user() && Auth::user()->role_id == 2)
                    @if($book->available)
                        <x-secondary-button class="bg-green-500" wire:click="$emit('triggerBorrow',{{ $book->id }})" wire:loading.attr="disabled">
                            {{ __('Borrow') }}
                        </x-secondary-button>
                    @endif
                @endif
            </x-slot>
        </form>
    </x-jet-dialog-modal>

    @push('js')
        <script>
            function resetBooks(){
                //Livewire.emit('resetBooks');
            }

            Livewire.on('triggerBorrow', bookId =>{
                axios.post('{{route('book.request')}}', {
                    id: bookId
                }).then(response => {
                    if (response.data.status == "OK") {
                        showToast("success", response.data.message);
                        Livewire.emit('reloadTable');
                    }else{
                        showToast("error", response.data.message);
                    }
                }).catch(e => {
                    showToast("error", "Whoops! Something went wrong.");
                });
                Livewire.emit('closeModal');
            });

            Livewire.on('Toast', title => {
                showToast("success",title);
            });

            function showToast(type,title){
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                })

                Toast.fire({
                    icon: type,
                    title: title
                });
            }
        </script>
    @endpush
</div>
