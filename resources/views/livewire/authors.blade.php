<div>
    <div class="flex flex-row-reverse">
        <div>
            <button class="m-5 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" wire:click="openModal()">
            <i class="fas fa-plus"></i> New Author</button>
        </div>
    </div>
    <div class="flex flex-col">
        <div class="p-4 flex sm:flex-row flex-col">
            <div class="flex flex-row mb-1 sm:mb-0">
                <div class="relative w-18">
                    <select wire:model = "showByPage"
                        class="appearance-none h-full rounded-l border block appearance-none w-full bg-white border-gray-400 text-gray-700 py-2 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        <option>5</option>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    </div>
                </div>
            </div>
            <div class="block relative w-full md:w-1/3 lg:w-1/3">
                <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                    <i class="fas fa-search"></i>
                </span>
                <input placeholder="Search" wire:model.debounce.200ms = "search"
                    class="appearance-none rounded-r rounded-l sm:rounded-l-none border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
            </div>
        </div>
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-x-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="table-auto min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                             <tr>
                                <th scope="col" class="max-w-1/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="max-w-1/5 relative px-6 py-3">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(count($authors))
                                @foreach($authors as $author)
                                <tr>
                                    <td class="w-auto px-6 py-4">
                                        <div class="text-sm text-gray-900">{{$author->name}}</div>
                                    </td>
                                    <td class="w-auto px-6 py-4 text-right text-sm font-medium">
                                        <div class="">
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="margin-right:20px;margin-bottom:5px;" 
                                            wire:click="openModal({{$author->id}})">
                                            <i class="fas fa-edit" style="margin-right:10px"></i>Edit</button>
                                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" style="margin-right:20px;margin-bottom:5px;" 
                                            wire:click="$emit('triggerDelete',{{ $author->id }})">
                                            <i class="fas fa-trash-alt" style="margin-right:10px"></i>Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="w-auto px-6 py-4">
                                        <div class="text-gray-900 text-center font-bold">No data to display</div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{$authors->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-jet-dialog-modal wire:model="triggerModal">
        <x-slot name="title">
            <h3>{{$edit ? 'Edit' : 'Add' }} Author</h3>
        </x-slot>
        <form wire:submit.prevent="submit">
            <x-slot name="content">
                <x-jet-label for="name" class="font-bold" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" wire:model="name" required wire:keydown.enter=saveAuthor() autocomplete="off"/>
                @error('name') <span class="error text-red-500">* {{ $message }}</span> @enderror
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button class="bg-gray-300" wire:click="$toggle('triggerModal')" wire:loading.attr="disabled">
                {{ __('Close') }}
                </x-secondary-button>
                <x-secondary-button class="{{$edit ? 'bg-blue-400' : 'bg-green-400' }}" wire:loading.attr="disabled" style="margin-left:20px;" wire:click="saveAuthor()">
                    {{$edit ? 'Update' : 'Add' }}
                </x-secondary-button>
            </x-slot>
        </form>
    </x-jet-dialog-modal>

    @push('js')
        <script>
            window.livewire.on('autofocus', inputID => { 
                setTimeout(function(){ 
                    document.getElementById(inputID).focus(); 
                }, 300);
            });

            Livewire.on('triggerDelete', authorId =>{
                Swal.fire({
                    title: 'Delete Author',
                    text: "Are you sure you want to delete this author?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#b2b7b8',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Close'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete('/authors/'+authorId).then(response => {
                            if (response.data.status == "OK") {
                                showToast("success", response.data.message);
                                Livewire.emit('reloadTable');
                            }else{
                                showToast("error", response.data.message);
                            }
                        }).catch(e => {
                            showToast("error", "Whoops! Something went wrong.");
                        })
                    }
                })
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